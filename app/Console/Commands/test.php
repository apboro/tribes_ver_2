<?php

namespace App\Console\Commands;

use App\Helper\PseudoCrypt;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\TariffController;
use App\Mail\ExceptionMail;
use App\Models\Community;
use App\Models\DonateVariant;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Models\User;
use App\Repositories\Messenger\MessengerRepository;
use App\Repositories\Messenger\MessengerRepositoryContract;
use App\Repositories\Payment\PaymentRepository;
use App\Services\TelegramMainBotService;
use App\Services\TelegramComponents\Storage;
use App\Services\Tinkoff\Payment as Pay;
use App\Services\Tinkoff\TinkoffApi;
use App\Services\Tinkoff\TinkoffE2C;
use Carbon\Carbon;
use App\Services\SMTP\Mailer;

use http\Client\Request;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use App\Services\Telegram;
use Nette\Utils\DateTime;
use Ramsey\Uuid\Rfc4122\VariantTrait;

//include_once(app_path() . '/Services/Api/madeline.php');

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test {--user=} {--tariff=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $messengerRepo;
    private $telegramBotService;
    private $pr;

    public function __construct(
        MessengerRepositoryContract $messengerRepo,
        TelegramMainBotService      $telegramBotService,
        PaymentRepository           $pr
    )
    {
        parent::__construct();
        $this->messengerRepo = $messengerRepo;
        $this->telegramBotService = $telegramBotService;
        $this->pr = $pr;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $telegramUsers = TelegramUser::with('tariffVariant')->get();
//        dd($telegramUsers->only('id'));
//
//        TelegramBotService::sendMessage(-612889716, "Выполнение планировщика");

        $v = Payment::find(1);
        dd($v->donates()->get());
        dd($payment);

//        $users = User::all();
//
////        foreach($users as $user){
////            dd($user->)
////        }
//
//
//        $variant = TarifVariant::find(5);
//        $follower = User::find(2);
////        $user = User::find(2);
//        $p = new Pay();
//        $p->amount($variant->price * 100)
//            ->charged(true)
//            ->payFor($variant)
//            ->community(null)
//            ->payer($follower)
//            ->telegram();
//
//        $payment = $p->pay();
//
//        dd($payment);
        return 1;
    }
}






















/*
   ______          __          __   __                __
  / ____/___  ____/ /__  _____/ /  / /___  ____   ___/ /____
 / /   / __ \/ __  / _ \/ ___/ /__/ / __ \/ __ \/ __  / __  \
/ /___/ /_/ / /_/ /  __/ /  _\___  / /_/ / /_/ / /_/ / /_/ / \
\____/\____/\____/\___/_/  /______/\____/\____/\____/\____/\/

*/
