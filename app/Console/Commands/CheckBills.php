<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\Tinkoff\Bill;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class CheckBills extends Command
{
    protected $signature = 'check:bills';
    protected $tinkoffBill;
    protected const DELAY_BETWEEN_CALLS = 50000;

    public function __construct(Bill $tinkoffBill)
    {
        parent::__construct();
        $this->tinkoffBill = $tinkoffBill;
    }

    public function handle()
    {
        try {
            $startTime = $this->tinkoffBill->geteDatesFoCheckStatus();
            $payments = Payment::findUnpaidBills($startTime);

            foreach ($payments as $payment) {
                $status = $this->tinkoffBill->getStatus($payment->bill_id);

                usleep(self::DELAY_BETWEEN_CALLS);
                if ($status == 'EXECUTED') {
                    $payment->status = 'CONFIRMED';
                    $payment->save();

                    $class = $payment->payable_type ?? null;
                    if ($class && method_exists($class, 'actionAfterPayment')) {
                        $class::actionAfterPayment($payment);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::alert('Ошибка при проверке статуса счетов Тинькофф', ['exception' => $e]);
        }

        return Command::SUCCESS;
    }
}