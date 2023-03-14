<?php

namespace App\Repositories\Payment;

use App\Filters\PaymentFilter;
use App\Helper\PseudoCrypt;
//use App\Http\Controllers\API\TinkoffMerchantAPI;
use App\Models\Community;
use App\Models\Payment;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffApi;
use App\Services\TinkoffE2C;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function Psy\debug;

class PaymentRepository implements PaymentRepositoryContract
{
    private $perPage = 15;
    public $e2c;
    protected TelegramLogService $telegramLogService;

    public function __construct(TelegramLogService $telegramLogService)
    {
        $this->telegramLogService = $telegramLogService;
        $this->e2c = new TinkoffE2C();
    }

    public function initPayment($data, $community)
    {
        $telegramId = ($data['telegram_id']) ? $data['telegram_id'] : '';
        
        $payment = $this->init(
            $data['amount'],
            $community,
            $data['from_name'],
            $data['comment'],
            $data['notify'],
            $data['type'],
            $telegramId,
            $data['recurrent'] ?? false,
            $data['recurrent_id'] ?? false,
            $data['user_id'] ?? null);

        return $payment;
    }

    public function getList(PaymentFilter $filters)
    {
        return Payment::filter($filters)->owned()->orderBy('created_at', 'DESC')->paginate($this->perPage);
    }



    public function init($amount, $community, $fromName, $comment, $notify, $type = NULL, $telegramId = NULL, $recurrent = false, $recurrent_id = false, $user_id = null)
    {
        $api = new TinkoffApi(env('TINKOFF_TERMINAL_KEY'), env('TINKOFF_SECRET_KEY'));
        $debugData = 'Инициальзация оплаты. ';
        $enabledTaxation = true;
        $isShipping = false;

        $debugData .= 'Cообщество: ' . $community->title . ' id = ' . $community->id . ' ';

        $amount = $amount * 100;

        $debugData .= 'Cумма в копейках: ' . $amount . ' ';

        $author = $community->owner()->first();

        $user = User::find($user_id);

        $debugData .= 'Автор: ' . ($author ? $author->id : 'null') . ' ';
        $debugData .= 'Плательщик: ' . ($user ? $user->id : 'null') . ' ';

        $accumulation = $author ? $author->getActiveAccumulation() : null;

        $debugData .= 'Накопление (id): ' . ($accumulation ? $accumulation->SpAccumulationId : 'null') . ' ';


        $receiptItem = [[
            'Name'          => 'Оплата за использование системы',
            'Price'         => $amount,
            'Quantity'      => 1,
            'Amount'        => $amount,
            'PaymentMethod' => TinkoffApi::$paymentMethod['full_prepayment'],
            'PaymentObject' => TinkoffApi::$paymentObject['service'],
            'Tax'           => TinkoffApi::$vats['none']
        ]];

        $receipt = [
            'EmailCompany' => '',
            'Phone'        => '', //Auth::user()->phone,
            'Taxation'     => TinkoffApi::$taxations['osn'],
            'Items'        => TinkoffApi::balanceAmount($isShipping, $receiptItem, $amount),
        ];

        $payment = new Payment();

        $payment->type = $type;
        $payment->community_id = $community->id;
        $payment->add_balance = $amount / 100;
        $payment->save();

        $payment->OrderId = $payment->id . date("_md_s");

        $params = [
            'NotificationURL' => route('tinkoff.notify'),
            'OrderId' => $payment->OrderId,
            'Amount'  => $amount,
            'SuccessURL' => route('payment.success', ['hash' => PseudoCrypt::hash($payment->id), 'telegram_id' => $telegramId]),
            'DATA'    => [
                'Email'           => $user ? $user->email : '', //,
            ],
        ];



        if($accumulation){
            $params['DATA']['StartSpAccumulation'] = false;
            $params['DATA']['SpAccumulationId'] = $accumulation->SpAccumulationId;
        } else {
            $params['DATA']['StartSpAccumulation'] = true;
        }

        if($recurrent){
            $user = User::find($user_id);
            $params['Recurrent'] = 'Y';
            $params['DATA']['CustomerKey'] = $user->getCustomerKey();
            $debugData .= 'Рекуррент: новый для ' . $user->getCustomerKey();
        }

//        if ($enabledTaxation) {
//            $params['Receipt'] = $receipt;
//        }
        $debugData .= 'Время: ' . Carbon::now()->format('Y-m-d H:i:s') . ' ';

        $this->telegramLogService->sendLogMessage($debugData);

        $api->init($params);




        unset($params['DATA']);
        unset($params['Receipt']);
        $params['Password'] = env('TINKOFF_SECRET_KEY');
        ksort($params);
        $token_str = '';

        foreach ($params as $param) {
            $token_str .= $param;
        }
        
        $payment->paymentId = $api->paymentId;
        $payment->amount = $amount;
        $payment->paymentUrl = $api->paymentUrl;
        $payment->response = $api->response;
        $payment->status = $api->status;
        $payment->token = hash('sha256', $payment->id);
        $payment->error = $api->error;
        $payment->from = $fromName ? $fromName : null;
        $payment->isNotify = isset($notify);
        $payment->comment = $comment ? $comment : null;
        $payment->save();

        return $payment;
    }

    public function getPaymentById($id)
    {
        return Payment::find($id);
    }

}
