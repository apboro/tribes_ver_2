<?php

namespace App\Repositories\Community;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Community;
use App\Models\CommunityRule;
use App\Models\RestrictedWord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommunityRuleRepository
{
    const TYPE_IMAGE_CONTENT = 'content_image';
    const TYPE_IMAGE_WARNING = 'warning_image';
    const TYPE_IMAGE_COMPLAINT = 'user_complaint_image';

    public function add(ApiRequest $request)
    {

        /** @var CommunityRule $community_rule */
        $community_rule = CommunityRule::create([
            'user_id' => Auth::user()->id,
            'name' => $request->input('name'),
            'content' => strip_tags($request->input('content')) ?? null,
            'warning' => $request->input('warning') ?? null,
            'max_violation_times' => $request->input('max_violation_times') ?? null,
            'action' => $request->input('action') ?? null,
            'complaint_text' => $request->input('complaint_text') ?? null,
            'quiet_on_restricted_words' => $request->input('quiet_on_restricted_words') ?? false,
            'quiet_on_complaint' => $request->input('quiet_on_complaint') ?? false,
        ]);

        if ($community_rule == null) {
            return false;
        }

        $this->addRestrictedWords($request, $community_rule);

        $this->uploadImages($request, $community_rule);

        if (!empty($request->input('community_ids'))) {
            $this->attachCommunities($request, $community_rule);
        }
        return $community_rule;
    }

    /**
     * @param ApiRequest $request
     * @param CommunityRule $community_rule
     * @return void
     */
    public function uploadImages(
        ApiRequest    $request,
        CommunityRule $community_rule
    )
    {
        if (!empty($request->file('warning_image'))) {
            $this->uploadFile($request, $community_rule, self::TYPE_IMAGE_WARNING);
        }
        if (!empty($request->file('content_image'))) {
            $this->uploadFile($request, $community_rule, self::TYPE_IMAGE_CONTENT);
        }
        if (!empty($request->file('user_complaint_image'))) {
            $this->uploadFile($request, $community_rule, self::TYPE_IMAGE_COMPLAINT);
        }
    }

    public function addRestrictedWords(ApiRequest $request, CommunityRule $community_rule)
    {
        if ($request->input('restricted_words') && !empty(array_filter($request->input('restricted_words')))) {
            if (Str::contains($request->input('restricted_words')[0], ',')) {
                $restrictedWords = explode(",", $request->input('restricted_words')[0]);
            } else {
                $restrictedWords = $request->input('restricted_words');
            }
            foreach ($restrictedWords as $word) {
                RestrictedWord::create([
                    'moderation_rule_uuid' => $community_rule->uuid,
                    'word' => trim($word) ?? null,
                ]);
            }
        }
    }

    /**
     * @param ApiRequest $request
     * @param CommunityRule $community_rule
     * @param string $type
     * @return void
     */
    public function uploadFile(
        ApiRequest    $request,
        CommunityRule $community_rule,
        string        $type
    )
    {
        $file = $request->file($type);
        $upload_folder = 'public/moderation_images';
        $extension = $file->getClientOriginalExtension();
        $filename = md5(rand(1, 1000000) . $file->getClientOriginalName() . time()) . '.' . $extension;
        Storage::putFileAs($upload_folder, $file, $filename);
        $community_rule->{$type . "_path"} = 'storage/moderation_images/' . $filename;
        $community_rule->save();
    }

    public function attachCommunities(
        ApiRequest    $request,
        CommunityRule $community_rule
    )
    {
        if (!empty($community_rule->communities)) {
            /** @var Community $community */
            foreach ($community_rule->communities as $community) {
                $community->moderation_rule_uuid = null;
                $community->save();
            }
        }
        foreach ($request->input('community_ids') as $community_id) {
            /** @var Community $community */
            $community = Community::where('id', $community_id)->where('owner', Auth::user()->id)->first();
            if ($community !== null) {
                $community->moderation_rule_uuid = $community_rule->uuid;
                $community->save();
            }
        }
    }

    public function updateCommunityRule(
        CommunityRule $community_rule,
        ApiRequest    $request
    )
    {
        $community_rule->fill([
            'name' => $request->input('name'),
            'content' => $request->input('content') ?? null,
            'warning' => $request->input('warning') ?? null,
            'max_violation_times' => $request->input('max_violation_times') ?? null,
            'action' => $request->input('action') ?? null,
            'complaint_text' => $request->input('complaint_text') ?? null,
            'quiet_on_restricted_words' => $request->input('quiet_on_restricted_words') ?? false,
            'quiet_on_complaint' => $request->input('quiet_on_complaint') ?? false,
        ]);
        $community_rule->save();
        $this->removeRestrictedWords($community_rule);

        $this->addRestrictedWords($request, $community_rule);

        $this->uploadImages($request, $community_rule);
        if (!empty($request->input('community_ids'))) {
            $this->attachCommunities($request, $community_rule);
        }
        $community_rule->load('communities');
        return $community_rule;

    }

    public function removeRestrictedWords(CommunityRule $community_rule): void
    {
        RestrictedWord::where('moderation_rule_uuid', $community_rule->uuid)->delete();
    }

}