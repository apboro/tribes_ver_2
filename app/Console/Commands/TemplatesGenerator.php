<?php

namespace App\Console\Commands;

use App\Models\Template;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class TemplatesGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'templates:generate';

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
        Template::truncate();

        foreach (Storage::disk('views')->files('common/course/templates') as $file) {
            $f = Storage::disk('views')->get($file);
            Template::create([
                'title' => explode('.', basename($file))[0],
                'preview' => 0,
                'html' => $f,
            ]);
        }
    }

}
