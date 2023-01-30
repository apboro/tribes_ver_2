<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;

class CheckCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
    public function handle(): int
    {
        $courses = Course::all();
        foreach ($courses as $course){
            $course->activation_date;
            $course->publication_date;
            $course->deactivation_date;
        }

        return 0;
    }
}
