<?php

namespace App\Repositories\Tariff;

use App\Models\TariffVariant;
use App\Models\Statistic;
use App\Models\UserIp;
use App\Repositories\File\FileRepositoryContract;
use App\Filters\TariffFilter;
use App\Models\Payment;
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
        if (isset($request->days)) 
            $this->updateDaysForUser($request, $community);

        if (isset($request->excluded))
            $this->excludedUser($request, $community);

        if (isset($request->tariff))
            $this->updateTariffForUser($request, $community);
    }

    /**
     * Обновить или добавить дату оплаты тарифа
     */
    private function updatePaymentDate($date, $time, $community, $ty)
    {
        $newDate = $date . ' ' . $time;
        if ($date !== null) {
             $this->createPayment($community->id, $ty->telegram_id, $newDate);
        } 
    }

    private function createPayment($communityId, $tyTelegramId, $date)
    {
        Payment::create([
            'OrderId' => 1,
            'community_id' => $communityId,
            'add_balance' => 0,
            'isNotify' => false,
            'telegram_user_id' => $tyTelegramId,
            'paymentUrl' => env('APP_DOMAIN'),
            'type' => 'tariff',
            'activated' => false,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }

    /**
     * Обновить тариф пользователю
     */
    private function updateTariffForUser($request, $community)
    {
        foreach ($request->tariff as $tyId => $variantId) {
            $ty = TelegramUser::find($tyId);
           
            if (isset($request->date_payment[$tyId]) && isset($request->time_payment[$tyId])) {
                $this->updatePaymentDate($request->date_payment[$tyId], $request->time_payment[$tyId], $community, $ty);
            } else { 
                if ($variantId)
                    $this->createPayment($community->id, $ty->telegram_id, now()->format('Y-m-d G:i:s'));
            }
            
            
            // if ($ty->telegram_id === config('telegram_bot.bot.botId') || $ty->user_id === $community->owner)        //Отключить возможность дать тариф автору и боту
            //     continue;

            $variantForThisCommunity = $ty->tariffVariant->where('tariff_id', $community->tariff->id)->first();

            if ($variantId === null) {
                if ($variantForThisCommunity) 
                    $ty->tariffVariant()->detach($variantForThisCommunity->id);
                    $payments = Payment::where('telegram_user_id', $ty->telegram_id)->where('type', 'tariff')->get();
                    foreach ($payments as $payment) {
                        $payment->delete();
                    }
                continue;
            }
            
            $variant = TariffVariant::find($variantId);
    
            if ($variantForThisCommunity) {
                $ty->tariffVariant()->detach($variantForThisCommunity->id);
                $ty->tariffVariant()->attach($variant, ['days' => $variant->period, 'prompt_time' => date('H:i')]);
            } else {
                $ty->tariffVariant()->attach($variant, ['days' => $variant->period, 'prompt_time' => date('H:i')]);
            } 
        }
    }

    /**
     * Обновить количество дней пользователю
     */
    private function updateDaysForUser($request, $community)
    {
        foreach ($request->days as $userId => $days) {
            $ty = TelegramUser::find($userId);
            $variantId = $ty->tariffVariant()->where('tariff_id', $community->tariff->id)->first()->id;
            $ty->tariffVariant()->updateExistingPivot($variantId, [
                'days' => $days,
            ]);
        }
    }

    /**
     * Забанить пользователя или отменить бан
     */
    private function excludedUser($request, $community)
    {
        foreach ($request->excluded as $userId => $excluded) {
            $ty = TelegramUser::find($userId);
            if ($ty->telegram_id === config('telegram_bot.bot.botId') || $ty->user_id === $community->owner)
                continue;

            if ($excluded != $ty->communities()->where('community_id', $community->id)->first()->pivot->excluded) {

                if ($excluded === true) {
                    $ty->communities()->updateExistingPivot($community->id, [
                        'excluded' => $excluded,
                    ]);
                    $this->mainServiceBot->kickUser(config('telegram_bot.bot.botName'), $ty->telegram_id, $community->connection->chat_id);
                }
                
                if ($excluded === false) {
                    $ty->communities()->updateExistingPivot($community->id, [
                        'excluded' => $excluded,
                    ]);
                    $this->mainServiceBot->unKickUser(config('telegram_bot.bot.botName'), $ty->telegram_id, $community->connection->chat_id);
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

//        if ($data['trial_period']  && env('USE_TRIAL_PERIOD')) {
//            $this->tariffModel->test_period = $data['trial_period'];
//        }

        $this->tariffModel->test_period = $data['trial_period']  && env('USE_TRIAL_PERIOD', true)
            ? $data['trial_period']
            : $this->tariffModel->test_period;

        $this->tariffModel->tariff_notification = $data['tariff_notification'];

        if ($data['editor_data']) {
            $this->tariffModel->main_description = trim($data['editor_data'], '"');
        }

        $this->storeImages($data);

        $this->tariffModel->save();

        // dd($data['send_to_community']);
        $this->sendToCommunityAction($data, $community);

        if ($data['trial_period'] && env('USE_TRIAL_PERIOD', true)) {
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
