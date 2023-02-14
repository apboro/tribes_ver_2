<?php

namespace App\Console\Commands;

use App\Jobs\SendEmails;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:course';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check courses status by time and send emails';

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
    public function handle(): int
    {
        $courses = Course::with('buyers')->whereNotNull('activation_date')->get();
        foreach ($courses as $course){
            $activationDate = $course->activation_date ? Carbon::parse($course->activation_date) : null;
            $publicationDate = $course->publication_date ? Carbon::parse($course->publication_date) : null;
            $deactivationDate = $course->deactivation_date ? Carbon::parse($course->deactivation_date) : null;

            $courseName = $course->title;
            $checkoutTime = Carbon::now()->setSeconds(0)->toDateTimeString();

            //ACTIVATE COURSE
            $mailBody='Курс доступен!';
            $activationTime = $activationDate->toDateTimeString();
            if ($activationTime == $checkoutTime)
            {
                $course->isActive = true;
                $course->save();
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс активирован!','Cервис Spodial', $view);
            }

            $mailBody = 'Курс будет доступен через 24 часа!';
            $activation_time_minus_24hrs = $activationDate->subDay()->toDateTimeString();
            if ($activation_time_minus_24hrs === $checkoutTime)
            {
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс скоро будет доступен','Cервис Spodial', $view);
            }

            //DEACTIVATE COURSE
            $mailBody = 'Курс деактивирован!';
            $deactivation_time = $deactivationDate->toDateTimeString();
            if ($deactivationDate && $deactivation_time === $checkoutTime)
            {
                $course->isActive = false;
                $course->save();
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс деактивирован','Cервис Spodial', $view);
            }

            $mailBody = 'Курс будет отключен через 24 часа!';
            $deactivation_time_minus_24hrs = $deactivationDate->subDay()->toDateTimeString();
            if ($deactivationDate && $deactivation_time_minus_24hrs === $checkoutTime)
            {
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс скоро будет деактивирован','Cервис Spodial', $view);
            }

            //PUBLIC COURSE
            $publication_time = $publicationDate->toDateTimeString();
            if ($publicationDate && $publication_time === $checkoutTime)
            {
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс деактивирован','Cервис Spodial', $view);
            }

        }

        return 0;
    }
}
