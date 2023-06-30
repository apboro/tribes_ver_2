<?php

namespace App\Repositories\Donate;

use App\Models\Donate;
use App\Models\DonateVariant;
use App\Repositories\File\FileRepositoryContract;
use App\Services\File\common\FileEntity;
use App\Helper\PseudoCrypt;
use App\Services\File\FileUploadService;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Askoldex\Teletant\Exception\TeletantException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;

class DonateRepository implements DonateRepositoryContract
{
    private $fileRepo;
    private $donateModel;
    protected TelegramMainBotService $mainBotService;
    private TelegramLogService $telegramLogService;
    private $fileUploadService;
    private $fileEntity;


    public function __construct(
        FileRepositoryContract $fileRepo,
        TelegramMainBotService $mainBotService,
        TelegramLogService     $telegramLogService,
        FileUploadService      $fileUploadService,
        FileEntity             $fileEntity
    )
    {
        $this->fileRepo = $fileRepo;
        $this->mainBotService = $mainBotService;
        $this->telegramLogService = $telegramLogService;
        $this->fileUploadService = $fileUploadService;
        $this->fileEntity = $fileEntity;
    }

    public function update($community, $request, $id = NULL)
    {
        $this->initDonateModel($id);

        if (!$this->donateModel->exists) {
            $this->clearImages();
        }

        $this->donateModel->community_id = $community->id;

        $arrIndex = [0];

        foreach ($community->donate as $donate) {
            $arrIndex[] = $donate->index ?? 0;
        }

        $this->donateModel->index = max($arrIndex) + 1;

        $this->updateDescriptions($request);

        $this->generateLink();

        $this->donateModel->save();

        $this->updateVariants($request);

        $this->autoPrompt($request);

        $this->storeImages($request);

        $this->donateModel->save();

        $this->sendToCommunityAction($request, $community, $this->donateModel->id);

        return $this->donateModel;
    }

    private function updateVariants($data)
    {
        foreach ($this->donateModel->variants as $dv) {
            if ($dv->variant_name === 'fix_sum_1'){
                $dv->isStatic = true;
                $dv->description = $data['fix_sum_1_button'];
                $dv->isActive = $data['fix_sum_1_is_active'];
                $dv->price = $data['fix_sum_1'] ?? null;
            }
            if ($dv->variant_name === 'fix_sum_2'){
                $dv->isStatic = true;
                $dv->description = $data['fix_sum_2_button'];
                $dv->isActive = $data['fix_sum_2_is_active'];
                $dv->price = $data['fix_sum_2'] ?? null;
            }
            if ($dv->variant_name === 'fix_sum_3'){
                $dv->isStatic = true;
                $dv->description = $data['fix_sum_3_button'];
                $dv->isActive = $data['fix_sum_3_is_active'];
                $dv->price = $data['fix_sum_3'] ?? null;
            }
            if ($dv->variant_name === 'random_sum'){
                $dv->isStatic = false;
                $dv->description = $data['random_sum_button'];
                $dv->isActive = $data['random_sum_is_active'];
                $dv->min_price = $data['random_sum_min'] ?? null;
                $dv->max_price = $data['random_sum_max'] ?? null;
            }
            $dv->currency = 0;
            $dv->save();
        }
    }

    private function storeImages($request)
    {
        $this->fileEntity->getEntity($request);

        if (isset($request['files'])) {
            foreach ($request['files'] as $key => $file) {
                $decoded = json_decode($file['crop']);
                if (isset($file['image']) && isset($decoded->isCrop)) {
                    $fileData['file'] = $file['image'];
                    $fileData['crop'] = $decoded->isCrop;
                    $fileData['cropData'] = $decoded->cropData;
                    $fileData['entity'] = $request['entity'];

                    $f = $this->fileUploadService->procRequest($fileData)[0];

                    switch ($key) {
                        case 'main':
                            $this->donateModel->main_image_id = $f->id;
                            break;
                        case 'prompt':
                            $this->donateModel->prompt_image_id = $f->id;
                            break;
                        case 'success':
                            $this->donateModel->success_image_id = $f->id;
                            break;
                    }
                }
                if ($file['delete'] == "true") {
                    switch ($key) {
                        case 'main':
                            $this->donateModel->main_image_id = 0;
                            break;
                        case 'prompt':
                            $this->donateModel->prompt_image_id = 0;
                            break;
                        case 'success':
                            $this->donateModel->success_image_id = 0;
                            break;
                    }
                }
            }
        }
    }

    public function generateLink()
    {
        $this->donateModel->inline_link = PseudoCrypt::hash(Carbon::now()->timestamp, 8);
    }

    private function autoPrompt($data)
    {
        if ($data['auto_prompt_time']) {
            $timeArray = explode(":", $data['auto_prompt_time']);
            $this->donateModel->prompt_at_hours = $timeArray[0];
            $this->donateModel->prompt_at_minutes = $timeArray[1];
        }
        $this->donateModel->isAutoPrompt = $data->isAutoPrompt;
    }

    private function clearImages()
    {
        $this->donateModel->main_image_id = 0;
        $this->donateModel->prompt_image_id = 0;
        $this->donateModel->success_image_id = 0;
    }

    private function initDonateModel($id)
    {
        if ($id !== NULL) {
            $this->donateModel = Donate::find($id);
        } else {
            $this->donateModel = new Donate();
        }
    }

    private function updateDescriptions($data)
    {
        if ($data['title']) {
            $this->donateModel->title = $data['title'];
        }

        if ($data['description']) {
            $this->donateModel->description = $data['description'];
        }

        if ($data['success_description']) {
            $this->donateModel->success_description = $data['success_description'];
        }

        if ($data['prompt_description']) {
            $this->donateModel->prompt_description = $data['prompt_description'];
        }
    }

    private function sendToCommunityAction($data, $community, $donateId)
    {
        $sendToCommunity = $data['send_to_community'];
        if ($sendToCommunity) {
            try {
                $this->mainBotService->sendDonateMessage(config('telegram_bot.bot.botName'), $community->connection->chat_id, $donateId);
            } catch (TeletantException $e) {
                $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            }
        }
    }

    public function getDonateById($id)
    {
        return Donate::find($id);
    }


    public function getDonatesByCommunities(array $communityIds): Collection
    {
        if ($communityIds[0] == 'all') {
            return Donate::whereHas('community', function ($query) {
                $query->where('owner', Auth::user()->id);
            })->get();
        } else {
            return Donate::whereIn('community_id', $communityIds)->get();
        }
    }

    public function store($data)
    {
        $this->donateModel = new Donate();
        $this->saveModel($data);
        $this->donateModel->user_id = $data['user_id'];
        $this->generateLink();
        $this->donateModel->save();
        $this->genereateVariants();
        $this->updateVariants($data);
        return $this->donateModel;
    }

    public function genereateVariants()
    {
        DonateVariant::create([
            'donate_id' => $this->donateModel->id,
            'variant_name' => 'fix_sum_1']);
        DonateVariant::create([
            'donate_id' => $this->donateModel->id,
            'variant_name' => 'fix_sum_2']);
        DonateVariant::create([
            'donate_id' => $this->donateModel->id,
            'variant_name' => 'fix_sum_3']);
        DonateVariant::create([
            'donate_id' => $this->donateModel->id,
            'variant_name' => 'random_sum']);
    }

    public function updateModel($data)
    {
        $this->donateModel = Donate::owned()->findOrFail($data['id']);
        $this->saveModel($data);
        $this->donateModel->save();
        $this->updateVariants($data);
        return $this->donateModel;
    }

    public function saveModel($data)
    {
        $this->donateModel->title = $data['title'];
        $this->donateModel->image = $data['image'];
        $this->donateModel->description = $data['description'];
        $this->donateModel->donate_is_active = $data['donate_is_active'];
    }
}
