<?php

namespace App\Console\Commands;

use App\Models\Accumulation;
use App\Services\Tinkoff\TinkoffE2C;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class CheckAccumulations extends Command
{
    protected $signature = 'check:accumulations';
    public TinkoffE2C $tinkoff;

    public function __construct(TinkoffE2C $tinkoff)
    {
        parent::__construct();
        $this->tinkoff = $tinkoff;
    }

    public function handle()
    {
        try {
            $accumulations = Accumulation::findNeedClose();
            foreach ($accumulations as $accumulation) {

                $cardsList = $this->tinkoff->getCardsList($accumulation->user);
                if (count($cardsList) === 0) {
                    Log::info('Try find card for payout, but user does not have them', ['cardsList' => $cardsList, 'accumulation' => $accumulation]);
                    continue;
                }
                $cardId = '';
                $cardNumber = '';
                foreach ($cardsList as $card) {
                    if ($card['CardId']) {
                        $cardId = $card['CardId'];
                        $cardNumber = $card['Pan'] ?? null;
                        break;
                    }
                }
                if (!$cardId || !$cardNumber) {
                    Log::info('Card for payout does not have id or number', ['cardId' => $cardId, 'cardNumber' => $cardNumber, 'accumulation' => $accumulation]);
                    continue;
                }

                $this->tinkoff->processPayout($accumulation, $cardId, $cardNumber);
            }
        } catch (\Exception $e) {
            Log::info('An error occurred while checking the accumulations', ['exception' => $e]);
        }

        return Command::SUCCESS;
    }
}