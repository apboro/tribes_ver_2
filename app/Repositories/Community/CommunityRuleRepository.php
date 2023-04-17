<?php

namespace App\Repositories\Community;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Community;
use App\Models\CommunityRule;
use App\Models\RestrictedWord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunityRuleRepository
{
    public function add(ApiRequest $request)
    {

        /** @var CommunityRule $community_rule */
        $community_rule = CommunityRule::create([
            'user_id' => Auth::user()->id,
            'name' => $request->input('name'),
            'content' => $request->input('content'),
            'warning' => $request->input('warning'),
            'max_violation_times' => $request->input('max_violation_times'),
            'action' => $request->input('action'),
        ]);

        if ($community_rule == null) {
            return false;
        }
        if (!empty($request->input('restricted_words'))) {
            $this->addRestrictedWords($request, $community_rule);
        }

        if (!empty($request->file('warning_image'))) {
            $this->uploadFile($request, $community_rule);
        }
        if (!empty($request->input('community_ids'))) {
            $this->attachCommunities($request, $community_rule);
        }
        return $community_rule;
    }

    public function addRestrictedWords(
        ApiRequest    $request,
        CommunityRule $community_rule
    )
    {
        foreach ($request->input('restricted_words') as $word) {
            RestrictedWord::create([
                'community_rule_id' => $community_rule->id,
                'word' => $word
            ]);
        }
    }

    public function uploadFile(
        ApiRequest    $request,
        CommunityRule $community_rule)
    {
        $file = $request->file('warning_image');
        $upload_folder = 'public/hello_message_images';
        $extension = $file->getClientOriginalExtension();
        $filename = md5($file->getClientOriginalName() . time()) . '.' . $extension;
        Storage::putFileAs($upload_folder, $file, $filename);
        $community_rule->warning_image_path = 'storage/hello_message_images/' . $filename;
        $community_rule->save();
    }

    public function attachCommunities(
        ApiRequest    $request,
        CommunityRule $community_rule
    )
    {

        foreach ($request->input('community_ids') as $community_id) {
            /** @var Community $community */
            $community = Community::where('id', $community_id)->where('owner', Auth::user()->id)->first();
            if ($community !== null) {
                $community->communityRule()->associate($community_rule);
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
            'content' => $request->input('content'),
            'warning' => $request->input('warning'),
            'max_violation_times' => $request->input('max_violation_times'),
            'action' => $request->input('action'),
        ]);
        $community_rule->save();
        $this->removeRestrictedWords($community_rule);
        $this->addRestrictedWords($request, $community_rule);
        if (!empty($request->file('warning_image'))) {
            $this->uploadFile($request, $community_rule);
        }
        if (!empty($request->input('community_ids'))) {
            $this->attachCommunities($request, $community_rule);
        }
    }

    public function removeRestrictedWords(CommunityRule $community_rule): void
    {
        RestrictedWord::where('community_rule_id', $community_rule->id)->delete();
    }

}