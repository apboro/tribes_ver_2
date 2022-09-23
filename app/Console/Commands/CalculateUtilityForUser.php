<?php

namespace App\Console\Commands;

use App\Models\Community;
use App\Models\TelegramMessage;
use App\Models\TelegramUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateUtilityForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:utility';

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
        $tu = 'telegram_users';
        $tm = 'telegram_messages';
        $com = 'communities';
        $tc = 'telegram_connections';
        $tuc = 'telegram_users_community';

        $communities = DB::table($com)->select('id')->get();
        foreach ($communities as $community) {
            $telegramUsers = DB::table($tu)
            ->join($tuc, "$tu.telegram_id", "=", "$tuc.telegram_user_id")
            ->where("$tuc.community_id", $community->id)
            ->leftJoin($com, "$tuc.community_id", "$com.id")
            ->leftJoin($tc, "$com.connection_id", "$tc.id")
            ->leftJoin($tm, function ($join) use ($tm, $tu, $tc) {
                $join->on("$tm.telegram_user_id", '=', "$tu.telegram_id")
                ->on("$tm.group_chat_id", '=', "$tc.chat_id")
                ->orOn("$tm.group_chat_id", '=', "$tc.comment_chat_id")
                ->on("$tm.telegram_user_id", '=', "$tu.telegram_id");
            })
            ->select('telegram_id', 'community_id', 'group_chat_id', 'utility', 'user_utility');
            
            $userUtilities = [];
            foreach ($telegramUsers->get() as $teleUser) {
                $userUtilities[$teleUser->telegram_id][] = $teleUser->utility ?? 0;
            }

            foreach ($userUtilities as $userId => $value) {
                DB::table($tuc)->where('community_id', $community->id)->where('telegram_user_id', $userId)
                ->update(['user_utility' => array_sum($value)]);
            }
        }
    }
}
