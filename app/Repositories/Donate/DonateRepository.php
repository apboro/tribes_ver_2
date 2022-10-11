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

        if ($data['donate']) {
            foreach ($data['donate'] as $donate) {

                $isStatic = array_key_exists('cost', $donate);
                $old  = !empty($donate['variant_id'])?$donate['variant_id']:null;
                $dv = $old ?
                    DonateVariant::find($old) :
                    new DonateVariant();
                if(empty($old)) {
                    $dv->donate_id = $this->donateModel->id;
                }
                $dv->isStatic = $isStatic;
                $dv->description = $donate['description'];
                $dv->isActive = isset($donate['status']);

                if (isset($donate['cost']) && $donate['cost'] > 0) {
                    $dv->price = $donate['cost'];
                }

                $dv->min_price = isset($donate['min_price']) ? $donate['min_price'] : null;
                $dv->max_price = isset($donate['max_price']) ? $donate['max_price'] : null;

                $dv->currency = Donate::$currency[$donate['currency'] ?? 'rub'];

                $dv->save();
            }
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
}
