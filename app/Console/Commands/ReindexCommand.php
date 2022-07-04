<?php

namespace App\Console\Commands;

use App\Models\Knowledge\Question;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex all questions in ElasticSearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Запуск переиндексации ElasticSearch');

        try {
            $this->elasticsearch->indices()->delete(['index' => 'knowledge.questions']);
        } catch (\Exception $exception)
        {
            $this->info($exception);
        }

        foreach (Question::cursor() as $question)
        {
            $this->elasticsearch->index([
                'index' => $question->getSearchIndex(),
                'type' => $question->getSearchType(),
                'id' => $question->getKey(),
                'body' => $question->toSearchArray(),
            ]);
            $this->output->write('>');
        }
        $this->info('\nDone!');
    }
}
