<?php

namespace App\Console\Commands;

use App\Models\Community;
use App\Models\Payment;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Mockery\Matcher\Pattern;

class CreateFakeStatistic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fake:add';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $communs = Community::all();

        $faker = Factory::create('ru_RU');
        $types = ['tariff', 'donate'];

        foreach($communs as $commun){

            for($d = 0; $d < 365; $d++){

                for($c = 0; $c < rand(0,148); $c++) {
                    $payment = new Payment();
                    $payment->OrderId = rand(100, 10000);
                    $payment->community_id = $commun->id;
                    $payment->add_balance = rand(100, 10000);
                    $payment->from = $faker->name;
                    $payment->comment = $faker->text(200);
                    $payment->telegram_user_id = 507752964;
                    $payment->status = 'CONFIRMED';
                    $payment->type = $types[rand(0,1)];
                    $payment->created_at = Carbon::now()->subDays($d);
                    $payment->save();
                }
            }
        }


        return 0;
    }
}
