<?php

namespace App\Jobs;

use App\Services\SMTP\Mailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\View\View;

class SendEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection  $recipients;
    private string $subject;
    private string $from;
    private string $view;
    private array  $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipients, $subject, $from, $view)
    {
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->from = $from;
        $this->view = $view;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->recipients as $recipient) {
            new Mailer($this->from, $this->view, $this->subject, $recipient->email);
        }
    }
}
