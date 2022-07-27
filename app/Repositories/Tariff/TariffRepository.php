<?php

namespace App\Repositories\Tariff;

use App\Models\TariffVariant;
use App\Models\Statistic;
use App\Models\UserIp;
use App\Repositories\File\FileRepositoryContract;
use App\Filters\TariffFilter;
use App\Models\TelegramUser;
use App\Services\TelegramMainBotService;
use Illuminate\Http\Request;

class TariffRepository implements TariffRepositoryContract
{
    private $fileRepo;
    private $tariffModel;
    public $perPage = 15;
    protected TelegramMainBotService $mainServiceBot;

    public function __construct(FileRepositoryContract $fileRepo, TelegramMainBotService $mainServiceBot)
    {
        $this->fileRepo = $fileRepo;
        $this->mainServiceBot = $mainServiceBot;
    }

    public function statisticView(Request $request, $community)
    {
        $ips = UserIp::where('ip', $request->getClientIp())
            ->where('statistic_id', $community->statistic->id)
            ->whereDate('created_at', date('Y-m-d'))
            ->get();

        $ipsAll = UserIp::where('ip', $request->getClientIp())
            ->where('statistic_id', $community->statistic->id)
            ->get();

        $statistic = Statistic::firstOrCreate([
            'community_id' => $community->id
        ]);

        if ($ips->first() == NULL) {
            UserIp::create([
                'ip' => $request->getClientIp(),
                'statistic_id' => $community->statistic->id,
            ]);
            if ($ipsAll->first() == NULL) {
                $statistic->hosts++;
            }
            $statistic->views++;
            $statistic->save();
        } else {
            $statistic->views++;
            $statistic->save();
        }
    }

    public function perm($request, $community)
    {
        if (isset($request->days)) {
            foreach ($request->days as $userId => $days) {
                $ty = TelegramUser::find($userId);
                $variantId = $ty->tariffVariant()->where('tariff_id', $community->tariff->id)->first()->id;
                $ty->tariffVariant()->updateExistingPivot($variantId, [
                    'days' => $days,
                ]);
            }
        }

        if (isset($request->excluded)) {
            foreach ($request->excluded as $userId => $excluded) {
                $ty = TelegramUser::find($userId);
                if ($excluded != $ty->communities()->where('community_id', $community->id)->first()->pivot->excluded) {

                    if ($excluded == true) {
                        $ty->communities()->updateExistingPivot($community->id, [
                            'excluded' => $excluded,
                        ]);
                        $this->mainServiceBot->kickUser(config('telegram_bot.bot.botName'), $ty->telegram_id, $community->connection->chat_id);
                    }
                    
                    if ($excluded == false) {
                        $ty->communities()->updateExistingPivot($community->id, [
                            'excluded' => $excluded,
                        ]);
                        $this->mainServiceBot->unKickUser(config('telegram_bot.bot.botName'), $ty->telegram_id, $community->connection->chat_id);
                    }
                }
                
            }
        }
    }

    public function getList(TariffFilter $filters, $community)
    {
        $followers = $community->followers();
        return $followers->filter($filters)->orderBy('created_at', 'DESC')->paginate($this->perPage);
    }

    public function updateOrCreate($community, $data, $variantId = NULL)
    {
        $this->initTariffModel($community);

        if ($variantId !== NULL) {
            $variant = TariffVariant::find($variantId);
        } else {
            $variant = new TariffVariant;
        }

        $variant->tariff_id = $this->tariffModel->id;
        $variant->title = $data['tariff_name'];
        $variant->price = $data['tariff_cost'];
        $variant->period = $data['tariff_pay_period'];
        $variant->isActive = $data['tariff'];
        $variant->save();

        $this->tariffWithUser($community, $variant);
    }

    private function tariffWithUser($community, $variant)
    {
        if ($community->tariff->variants->first() == NULL) {
            $ty = $community->followers()->get();
            $days = ($community->tariff->test_period !== 0) ? $community->tariff->test_period : 7;
            foreach ($ty as $user) {
                $user->tariffVariant()->attach($variant, ['days' => $days, 'prompt_time' => date('H:i')]);
            }
        }
    }

    public function activate($variantId, $activate)
    {
        $variant = TariffVariant::find($variantId);
        $variant->isActive = $activate;
        $variant->save();
    }

    public function settingsUpdate($community, $data)
    {

        $this->initTariffModel($community);

        if (!$this->tariffModel->exists) {
            $this->clearImages();
        }

        $this->updateDescriptions($data);

        if ($data['title']) {
            $this->tariffModel->title = $data['title'];
        }

        if ($data['trial_period']) {
            $this->tariffModel->test_period = $data['trial_period'];
        }

        $this->tariffModel->tariff_notification = $data['tariff_notification'];

        if ($data['editor_data']) {
            $this->tariffModel->main_description = trim($data['editor_data'], '"');
        }

        $this->storeImages($data);

        $this->tariffModel->save();

        // dd($data['send_to_community']);
        $this->sendToCommunityAction($data, $community);

        if ($data['trial_period']) {
            $trialData = [
                'tariff_name' => 'Пробный период',
                'tariff_cost' => 0,
                'tariff_pay_period' => $data['trial_period'],
                'tariff' => ($data['trial_period'] !== 0) ? true : false
            ];

            $variantId = NULL;
            foreach ($community->tariff->variants as $variant) {
                if ($variant->price == 0 && $variant->isActive == true) {
                    $variantId = $variant->id;
                }
            }

            $this->updateOrCreate($community, $trialData, $variantId);
        }
    }

    private function storeImages($data)
    {
        if (isset($data['files'])) {
            foreach ($data['files'] as $key => $file) {
                if (isset($file['crop'])) {
                    $decoded = json_decode($file['crop']);
                    if (isset($file['image'])) {
                        $fileData['file'] = $file['image'];
                        $fileData['crop'] = $decoded->isCrop;
                        $fileData['cropData'] = $decoded->cropData;
                        $f = $this->fileRepo->storeFile($fileData);

                        switch ($key) {
                            case 'pay':
                                $this->tariffModel->main_image_id = $f->id;
                                break;
                            case 'welcome':
                                $this->tariffModel->welcome_image_id = $f->id;
                                break;
                            case 'success':
                                $this->tariffModel->thanks_image_id  = $f->id;
                                break;
                            case 'reminder':
                                $this->tariffModel->reminder_image_id  = $f->id;
                                break;
                            case 'publication':
                                $this->tariffModel->publication_image_id  = $f->id;
                                break;
                        }
                    }
                }
                if ($file['delete'] == "true") {

                    switch ($key) {
                        case 'pay':
                            $this->tariffModel->main_image_id = 0;
                            break;
                        case 'welcome':
                            $this->tariffModel->welcome_image_id = 0;
                            break;
                        case 'success':
                            $this->tariffModel->thanks_image_id  = 0;
                            break;
                        case 'reminder':
                            $this->tariffModel->reminder_image_id  = 0;
                            break;
                        case 'publication':
                            $this->tariffModel->publication_image_id  = 0;
                            break;
                    }
                }
            }
        }
    }

    private function updateDescriptions($data)
    {
        $this->tariffModel->welcome_description = $data['welcome_description'] ?? null;

        $this->tariffModel->reminder_description = $data['reminder_description'] ?? null;

        $this->tariffModel->thanks_description = $data['success_description'] ?? null;

        $this->tariffModel->main_description = $data['main_description'] ?? null;

        $this->tariffModel->publication_description = $data['publication_description'] ?? null;
    }

    private function clearImages()
    {
        $this->tariffModel->main_image_id = 0;
        $this->tariffModel->welcome_image_id = 0;
        $this->tariffModel->thanks_image_id  = 0;
        $this->tariffModel->reminder_image_id  = 0;
        $this->tariffModel->publication_image_id  = 0;
    }

    private function initTariffModel($community)
    {
        $this->tariffModel = $community->tariff()->firstOrNew();
    }

    private function sendToCommunityAction($data, $community)
    {
        if ($data['send_to_community'] == true) {
            $this->mainServiceBot->sendTariffMessage(config('telegram_bot.bot.botName'), $community);
        }
    }
}
