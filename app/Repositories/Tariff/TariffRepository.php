<?php

namespace App\Repositories\Tariff;

use App\Helper\PseudoCrypt;
use App\Http\ApiRequests\ApiRequest;
use App\Models\Community;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\Statistic;
use App\Models\UserIp;
use App\Repositories\File\FileRepositoryContract;
use App\Services\File\common\FileEntity;
use App\Services\File\FileUploadService;
use App\Filters\TariffFilter;
use App\Models\Payment;
use App\Models\TelegramUser;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isTrue;

class TariffRepository implements TariffRepositoryContract
{
    private $fileRepo;
    private Tariff $tariffModel;
    public $perPage = 15;
    protected TelegramMainBotService $mainServiceBot;
    private $fileUploadService;
    private $fileEntity;

    public function __construct(
        FileRepositoryContract $fileRepo,
        TelegramMainBotService $mainServiceBot,
        FileUploadService      $fileUploadService,
        FileEntity             $fileEntity
    )
    {
        $this->fileRepo = $fileRepo;
        $this->mainServiceBot = $mainServiceBot;
        $this->fileUploadService = $fileUploadService;
        $this->fileEntity = $fileEntity;
    }

    public function statisticView(Request $request, $community)
    {
        $ips = UserIp::where('ip', $request->getClientIp())
            ->where('statistic_id', $community->statistic->id ?? null)
            ->whereDate('created_at', date('Y-m-d'))
            ->get();

        $ipsAll = UserIp::where('ip', $request->getClientIp())
            ->where('statistic_id', $community->statistic->id ?? null)
            ->get();

        $statistic = Statistic::firstOrCreate([
            'community_id' => $community->id
        ]);


        if ($ips->first() == NULL) {
            UserIp::create([
                'ip' => $request->getClientIp(),
                'statistic_id' => $community->statistic->id ?? null,
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
            $this->createPayment($community, $ty->telegram_id, $newDate);
        }
    }

    private function createPayment($community, $tyTelegramId, $date)
    {
        $ty = TelegramUser::where('telegram_id', $tyTelegramId)->first();
        $variant = $ty->tariffVariant()->where('tariff_id', $community->tariff->id)->first();
        if (!$variant) {
            Payment::create([
                'OrderId' => 1,
                'community_id' => $community->id,
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
    }

    /**
     * Обновить тариф пользователю
     */
    private function updateTariffForUser($request, $community)
    {
        foreach ($request->tariff as $tyId => $variantId) {
            $ty = TelegramUser::find($tyId);

            if (isset($request->date_payment[$tyId]) && !$variantId || isset($request->time_payment[$tyId]) && !$variantId) {
                return redirect()->back()->withCommunity($community)->withErrors('Невозможно установить дату платежа без тарифа.');
            } elseif (isset($request->date_payment[$tyId]) || isset($request->time_payment[$tyId])) {

                if (isset($request->date_payment[$tyId]) && $request->date_payment[$tyId] !== now()->format('Y-m-d')) {
                    $date1 = new DateTime(now()->format('Y-m-d'));
                    $date2 = new DateTime($request->date_payment[$tyId]);
                    if ($date1 < $date2) {
                        return redirect()->back()->withCommunity($community)->withErrors('Невозможно установить дату платежа будущим числом.');
                    }
                }

                $this->updatePaymentDate($request->date_payment[$tyId] ?? now()->format('Y-m-d'), $request->time_payment[$tyId] ?? now()->format('G:i:s'), $community, $ty);
            } else {
                if ($variantId)
                    $this->createPayment($community, $ty->telegram_id, now()->format('Y-m-d G:i:s'));
            }


            // if ($ty->telegram_id === config('telegram_bot.bot.botId') || $ty->user_id === $community->owner)        //Отключить возможность дать тариф автору и боту
            //     continue;

            $variantForThisCommunity = $ty->tariffVariant->where('tariff_id', $community->tariff->id)->first();

            if ($variantId === null) {
                if ($variantForThisCommunity)
                    $ty->tariffVariant()->detach($variantForThisCommunity->id);
                $payments = Payment::where('telegram_user_id', $ty->telegram_id)->where('OrderId', '1')->where('type', 'tariff')->get();
                foreach ($payments as $payment) {
                    $payment->delete();
                }
                continue;
            }

            $variant = TariffVariant::find($variantId);

            $days = $variant->period;
            if (isset($request->date_payment[$tyId]) && $request->date_payment[$tyId] !== now()->format('Y-m-d')) {
                $date1 = new DateTime(now()->format('Y-m-d'));
                $date2 = new DateTime($request->date_payment[$tyId]);
                $difference = date_diff($date1, $date2);

                $days = $variant->period - $difference->days;
                if ($days < 0) {
                    $days = 0;
                }
            }

            if ($variantForThisCommunity) {
                if ($variantForThisCommunity->id !== $variant->id) {
                    $ty->tariffVariant()->detach($variantForThisCommunity->id);
                    $ty->tariffVariant()->attach($variant, ['days' => $days, 'prompt_time' => date('H:i'), 'isAutoPay' => false]);
                }
            } else {
                $ty->tariffVariant()->attach($variant, ['days' => $days, 'prompt_time' => date('H:i'), 'isAutoPay' => false]);
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
        try {
            foreach ($request->excluded as $userId => $excluded) {
                $ty = TelegramUser::find($userId);
                $role = $ty->communities()->find($community->id)->pivot->role;
                if ($ty->telegram_id === config('telegram_bot.bot.botId') || $ty->user_id === $community->owner || $role === 'administrator')
                    continue;

                $tariffVariant = $ty->tariffVariant()->where('tariff_id', $community->tariff->id)->first();

                if ($excluded != $ty->communities()->where('community_id', $community->id)->first()->pivot->excluded) {

                    if ($excluded === true) {
                        $this->mainServiceBot->kickUser(config('telegram_bot.bot.botName'), $ty->telegram_id, $community->connection->chat_id);
                        $ty->communities()->updateExistingPivot($community->id, [
                            'excluded' => $excluded,
                            'exit_date' => time()
                        ]);

                        if ($tariffVariant)
                            $ty->tariffVariant()->updateExistingPivot($tariffVariant->id, ['isAutoPay' => false, 'days' => 0]);
                    }

                    if ($excluded === false) {
                        $this->mainServiceBot->unKickUser(config('telegram_bot.bot.botName'), $ty->telegram_id, $community->connection->chat_id);
                        $ty->communities()->updateExistingPivot($community->id, [
                            'excluded' => $excluded,
                            'accession_date' => time()
                        ]);

                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    public function getList(TariffFilter $filters, $community)
    {
        if (empty($community)) {
            return (new Community())->followers()->paginate();
        }
        $followers = $community->followers();
        $followers = $filters->apply($followers);
        return $followers->paginate($this->perPage);
    }


    public function getTariffVariantsByCommunities(array $communityIds, $isActive = true, $isPersonal = null): Collection
    {
        $builder = TariffVariant::where('price', '>', 0)
            ->orderBy('number_button', 'ASC');
        if ($communityIds[0] == 'all') {
            $builder->whereHas('tariff', function ($query) {
                $query->whereHas('community', function ($query) {
                    $query->where('owner', Auth::user()->id);
                });
            });
        } else {
            $builder->whereHas('tariff', function ($query) use ($communityIds) {
                $query->whereIn('community_id', $communityIds);
            });
        }
        $builder->where('isActive', $isActive);
        if ($isPersonal !== null) {
            $builder->where('isPersonal', $isPersonal);
        }

        return $builder->get();
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
        $variant->isPersonal = $data['isPersonal'] ?? false;
        if ($variant->isPersonal) {
            $variant->isActive = true;
        } else {
            $variant->isActive = $data['tariff'] ?? false;
        }
        $variant->number_button = $data['number_button'] ?? null;
        $variant->arbitrary_term = $data['arbitrary_term'] ?? false;

        if (empty($variant->inline_link)) {
            $this->generateLink($variant);
        }
        $variant->save();

//        $this->tariffWithUser($community, $variant);
    }

    /**
     * @param TariffVariant|Tariff $variant
     * @return void
     */
    public function generateLink($variant)
    {
        $variant->inline_link = PseudoCrypt::hash(Carbon::now()->timestamp . rand(1, 99999999999), 8);
    }

    //если заводим первый тариф, то всех юзеров к нему привязываем
    private function tariffWithUser($community, $variant)
    {
        if ($community->tariff->variants->first() == NULL) {
            $ty = $community->followers()->get();
            $days = ($community->tariff->test_period != 0) ? $community->tariff->test_period : 7;
            foreach ($ty as $user) {
                $user->tariffVariant()->attach($variant, ['days' => $days, 'prompt_time' => date('H:i')]);
            }
        }
    }

    public function activate($variantId, $activate)
    {
        $variant = TariffVariant::find($variantId);
        $variant->isActive = $activate ?? false;
        $variant->save();
    }

    public function settingsUpdate($community, $data)
    {
//        dd($data->all());
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

        $this->tariffModel->test_period = isset($data['trial_period']) ? $data['trial_period'] : $this->tariffModel->test_period;

        $this->tariffModel->tariff_notification = $data['tariff_notification'];

        if ($data['editor_data']) {
            $this->tariffModel->main_description = trim($data['editor_data'], '"');
        }
        if ($data['welcome_editor_data']) {
            $this->tariffModel->welcome_description = trim($data['welcome_editor_data'], '"');
        }

        $this->storeImages($data);

        $this->tariffModel->save();

        $this->sendToCommunityAction($data, $community);

        if (isset($data['trial_period']) && env('USE_TRIAL_PERIOD', true)) {
            $trialData = [
                'tariff_name' => 'Пробный период',
                'tariff_cost' => 0,
                'tariff_pay_period' => $data['trial_period'],
                'tariff' => $data['trial_period'] != 0
            ];

            $variantId = NULL;
            foreach ($community->tariff->variants as $variant) {
                if ($variant->price == 0 && $variant->title = 'Пробный период') {
                    $variantId = $variant->id;
                }
            }

            $this->updateOrCreate($community, $trialData, $variantId);
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

                    $this->fileUploadService->modelsFile = new \Illuminate\Database\Eloquent\Collection();

                    switch ($key) {
                        case 'pay':
                            $this->tariffModel->main_image_id = $f->id;
                            break;
                        case 'welcome':
                            $this->tariffModel->welcome_image_id = $f->id;
                            break;
                        case 'reminder':
                            $this->tariffModel->reminder_image_id = $f->id;
                            break;
                        case 'success':
                            $this->tariffModel->thanks_image_id = $f->id;
                            break;
                        case 'publication':
                            $this->tariffModel->publication_image_id = $f->id;
                            break;
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
                            $this->tariffModel->thanks_image_id = 0;
                            break;
                        case 'reminder':
                            $this->tariffModel->reminder_image_id = 0;
                            break;
                        case 'publication':
                            $this->tariffModel->publication_image_id = 0;
                            break;
                    }
                }
            }
        }
    }

    private function updateDescriptions($data)
    {
        if (isset($data['welcome_description'])) {
//            $this->tariffModel->welcome_description = $data['welcome_description'];
            $this->tariffModel->welcome_description = trim($data['editor_data'], '"');
        }
        if (isset($data['reminder_description'])) {
            $this->tariffModel->reminder_description = $data['reminder_description'];
        }
        if (isset($data['success_description'])) {
            $this->tariffModel->thanks_description = $data['success_description'];
        }
        if (isset($data['main_description'])) {
            $this->tariffModel->main_description = $data['main_description'];
        }
        if (isset($data['publication_description'])) {
            $this->tariffModel->publication_description = $data['publication_description'];
        }
    }

    private function clearImages()
    {
        $this->tariffModel->main_image_id = 0;
        $this->tariffModel->welcome_image_id = 0;
        $this->tariffModel->thanks_image_id = 0;
        $this->tariffModel->reminder_image_id = 0;
        $this->tariffModel->publication_image_id = 0;
    }

    private function initTariffModel($community)
    {
        $this->tariffModel = $community->tariff()->firstOrNew();
        if (empty($this->tariffModel->inline_link)) {
            $this->generateLink($this->tariffModel);
        }
    }

    private function sendToCommunityAction($data, $community)
    {
        if ($data['send_to_community'] == true) {
            $this->mainServiceBot->sendTariffMessage(config('telegram_bot.bot.botName'), $community);
        }
    }

    public function createTarif($community)
    {
        $tariff = new Tariff();
        $this->initTariffModel($community);
        $baseAttributes = Tariff::baseData();
        $this->generateLink($tariff);
        $baseAttributes['inline_link'] = $tariff->inline_link;
        return $baseAttributes;
    }

    public function store($data): Tariff
    {
        $community = Community::owned()->findOrFail($data['community_id']);

        $this->initTariffModel($community);

        $this->tariffModel->title = $data['title'];
        $this->tariffModel->test_period_is_active = $data['test_period_is_active'] ?? false;
        $this->tariffModel->test_period = 3;
        $this->tariffModel->main_description = $data['main_description'] ?? null;
        $this->tariffModel->thanks_message = $data['thanks_message'] ?? null;
        $this->tariffModel->tariff_is_payable = $data['tariff_is_payable'] ?? false;
        $this->tariffModel->thanks_message_is_active = $data['thanks_message_is_active'] ?? false;
        $this->tariffModel->main_image = $data['main_image'] ?? null;
        $this->tariffModel->thanks_image = $data['thanks_image'] ?? null;
        $this->tariffModel->user_id = Auth::user()->id;
        $this->tariffModel->save();

        $variants = $this->tariffModel->variants()->get();
        if ($variants->isNotEmpty()) {
            foreach ($variants as $variant) {
                $variant->title = $variant->title === 'Пробный период' ? 'Пробный период' : $data['title'] ?? null;
                $variant->price = $variant->price == 0 ? 0 : $data['price'] ?? null;
                $variant->period = $variant->period === 3 ? 3 : 30;
                $variant->isActive = $data['tariff_is_payable'] ?? false;
                $variant->save();
            }
        } else {
            $this->generateVariants($data);

        }

        return $this->tariffModel;
    }

    public function update(ApiRequest $request): Tariff
    {
        $this->tariffModel = Tariff::findOrFail($request->id);
        $data = $request->all();
        $variant_data['title'] = $data['title'] ?? null;
        $variant_data['price'] = $data['price'] ?? null;
        $variant = $this->tariffModel->variants()->first();
        $variant->fill(array_filter($variant_data));
        $variant->save();

        unset($data['price']);
        $this->tariffModel->fill($data);
        $this->tariffModel->save();

        return $this->tariffModel;
    }

    public function generateVariants($data)
    {
        TariffVariant::create([
            'tariff_id' => $this->tariffModel->id,
            'title' => $data['title'] ?? null,
            'price' => $data['price'],
            'period' => 30,
            'isActive' => $data['tariff_is_payable'] ?? false,
            'inline_link' => PseudoCrypt::hash(Carbon::now()->timestamp . rand(1, 99999999999), 8),
        ]);
        TariffVariant::create([
            'tariff_id' => $this->tariffModel->id,
            'title' => 'Пробный период',
            'price' => 0,
            'period' => 3,
            'isActive' => $data['test_period_is_active'] ?? false,
            'inline_link' => PseudoCrypt::hash(Carbon::now()->timestamp . rand(1, 99999999999), 8),
        ]);

    }

    public function filter(ApiRequest $request)
    {
        $builder = Tariff::owned()->with(['community', 'tariffCommunityUsers']);

        $builder->when($request->community_title, function ($query) use ($request) {
            $query->whereHas('community', function ($q) use ($request) {
                $q->where('title', 'ilike', '%' . $request->community_title . '%');
            });
        });
        $builder->when($request->tariff_title, function ($query) use ($request) {
            $query->where('tariffs.title', 'ilike', '%' . $request->tariff_title . '%');
        });
        $builder->select(['tariffs.*', 'tarif_variants.price'])
            ->leftJoin('tarif_variants', function ($join) {
                $join->on('tariffs.id', '=', 'tarif_variants.tariff_id')
                    ->where('tarif_variants.price', '!=', 0);
            })->withCount('tariffCommunityUsers as followers');

        $builder->orderBy($request->sort_field ?? 'id', $request->sort_direction ?? 'asc');

        $builder->skip($request->offset)
            ->take($request->limit);

        return $builder;
    }

}
