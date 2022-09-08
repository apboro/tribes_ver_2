<?php

namespace App\Providers;

use App\Repositories\File\FileRepositoryContract;
use App\Repositories\Statistic\MediaProductStatisticRepository;
use App\Repositories\Statistic\MediaProductStatisticRepositoryContract;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
//use Elasticsearch\ClientBuilder;
//use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;

class AccessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(
            \App\Repositories\Statistic\TeleDialogStatisticRepositoryContract::class,
            \App\Repositories\Statistic\TeleDialogStatisticRepository::class
        );
        $this->app->bind(
            \App\Repositories\Statistic\FinanceStatisticRepositoryContract::class,
            \App\Repositories\Statistic\FinanceStatisticRepository::class
        );
        $this->app->bind(
            \App\Repositories\Author\AuthorRepositoryContract::class,
            \App\Repositories\Author\AuthorRepository::class
        );
        $this->app->bind(
            \App\Repositories\Knowledge\KnowledgeRepositoryContract::class,
            \App\Repositories\Knowledge\KnowledgeRepository::class
        );
        $this->app->bind(
            \App\Repositories\Messenger\MessengerRepositoryContract::class,
            \App\Repositories\Messenger\MessengerRepository::class
        );
        $this->app->bind(
            \App\Repositories\Donate\DonateRepositoryContract::class,
            \App\Repositories\Donate\DonateRepository::class
        );
        $this->app->bind(
            \App\Repositories\Community\CommunityRepositoryContract::class,
            \App\Repositories\Community\CommunityRepository::class
        );
        $this->app->bind(
            \App\Repositories\File\FileRepositoryContract::class,
            \App\Repositories\File\FileRepository::class
        );
        $this->app->bind(
            \App\Repositories\Notification\NotificationRepositoryContract::class,
            \App\Repositories\Notification\Sms16Repository::class
        );
        $this->app->bind(
            \App\Repositories\Statistic\StatisticRepositoryContract::class,
            \App\Repositories\Statistic\StatisticRepository::class
        );
        $this->app->bind(
            \App\Repositories\Transaction\TransactionRepositoryContract::class,
            \App\Repositories\Transaction\TransactionRepository::class
        );
        $this->app->bind(
            \App\Repositories\Tariff\TariffRepositoryContract::class,
            \App\Repositories\Tariff\TariffRepository::class
        );
        $this->app->bind(
            \App\Repositories\Video\VideoRepositoryContract::class,
            \App\Repositories\Video\VideoRepository::class
        );
        $this->app->bind(
            \App\Repositories\Lesson\LessonRepositoryContract::class,
            \App\Repositories\Lesson\LessonRepository::class
        );
        $this->app->bind(
            \App\Repositories\Course\CourseRepositoryContract::class,
            \App\Repositories\Course\CourseRepository::class
        );
        $this->app->bind(
            \App\Repositories\Payment\PaymentRepositoryContract::class,
            \App\Repositories\Payment\PaymentRepository::class
        );
        $this->app->bind(
            \App\Repositories\Follower\FollowerRepositoryContract::class,
            \App\Repositories\Follower\FollowerRepository::class
        );

        $this->app->bind(
            MediaProductStatisticRepositoryContract::class,
            MediaProductStatisticRepository::class
        );

        $this->app->bind(
            \App\Repositories\Telegram\TeleMessageRepositoryContract::class,
            \App\Repositories\Telegram\TeleMessageRepository::class
        );

        $this->app->bind(
            \App\Repositories\Telegram\TelePostRepositoryContract::class,
            \App\Repositories\Telegram\TelePostRepository::class
        );

        $this->app->bind(
            \App\Repositories\Telegram\TelePostViewsReposirotyContract::class,
            \App\Repositories\Telegram\TelePostViewsReposirory::class
        );

        $this->app->bind(
            \App\Repositories\Telegram\TeleMessageReactionRepositoryContract::class,
            \App\Repositories\Telegram\TeleMessageReactionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Telegram\TelePostReactionRepositoryContract::class,
            \App\Repositories\Telegram\TelePostReactionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Telegram\TeleDictReactionRepositoryContract::class,
            \App\Repositories\Telegram\TeleDictReactionRepository::class
        );

        $this->bindSearchClient();

    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))//['localhost', '9200'])
                ->build();
        });
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
