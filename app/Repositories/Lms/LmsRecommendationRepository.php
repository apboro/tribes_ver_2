<?php

namespace App\Repositories\Lms;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\LMSFeedback;
use App\Models\Publication;
use App\Models\Webinar;
use App\Models\VisitedPublication;
use App\Http\ApiRequests\Lms\ApiLmsRecommendationRequest;

class LmsRecommendationRepository
{

    protected $community_ids;
    protected $webinar_ids;
    protected $author;

    public function __construct(ApiLmsRecommendationRequest $request)
    {
        $this->community_ids = $request->community_ids ?? [];
        $this->webinar_ids = $request->webinar_ids ?? [];
        $this->author =  Auth::user()->author;
    }

    /**
     * Добавляет в массив $what (количество отзывов) данные из $feedback
     */
    private function countWhatTo(&$what, $feedback)
    {
        if ($feedback['all_ok']) {
            $what['all_ok'] = ($what['all_ok'] ?? 0) + 1;
        } else {
            if (is_iterable($feedback['options'])) {
                foreach ($feedback['options'] as $value) {
                    $what[$value] = ($what[$value] ?? 0) + 1;
                }
            }
        }
    }

    /**
     * Получает feedback о публикациях и вебинарах
     */
    private function getFeedbacks()
    {
        return LMSFeedback::where('author_id', $this->author->id)
            ->when(!empty($this->community_ids), function ($query) {
                return $query->whereIn('publication_id', $this->community_ids);
            })
            ->when(!empty($this->webinar_ids), function ($query) {
                return $query->whereIn('webinar_id', $this->webinar_ids);
            })
            ->get();
    }

    /**
     * Считает статистику по отзывам о семирнарах и вебинарах
     */
    private function getMaterialsStat($feedbacks): array
    {
        $likeMaterial = [];
        $enoughMaterial = [];
        $whatToAdd = [];
        $whatToRemove = [];
        foreach ($feedbacks as $feedback) {
            $likeMaterial[$feedback->like_material] = ($likeMaterial[$feedback->like_material] ?? 0) + 1;
            $enoughMaterial[$feedback->enough_material] = ($enoughMaterial[$feedback->enough_material] ?? 0) + 1;
            $this->countWhatTo($whatToAdd, $feedback->what_to_add);
            $this->countWhatTo($whatToRemove, $feedback->what_to_remove);
        }
        return  [
            'likeMaterial' => $likeMaterial,
            'enoughMaterial' => $enoughMaterial,
            'whatAdd' => $whatToAdd,
            'whatRemove' => $whatToRemove,
        ];
    }

    /**
     * Массив публикаций
     */
    private function getPublicationList(): array
    {
        return $this->author->publications()
            ->select('id')
            ->when((!empty($this->webinar_ids) || !empty($this->community_ids)), function ($query) {
                return $query->whereIn('id', $this->community_ids);
            })
            ->get()->pluck('id')->toArray() ?? [];
    }

    /**
     * Массив вебинаров
     */
    private function getWebinarList(): array
    {
        return $this->author->webinars()
            ->select('id')
            ->when((!empty($this->webinar_ids) || !empty($this->community_ids)), function ($query) {
                return $query->whereIn('id', $this->webinar_ids);
            })
            ->get()->pluck('id')->toArray() ?? [];
    }

    /**
     * Возвращает количество просмотров  публикаций из массива $publications
     */
    private function getVisitedPublicationsCount($publications): int
    {
        return VisitedPublication::whereIn('publication_id', $publications)->count() ?? 0;
    }

    /**
     * Возвращает количество просмотров вебинаров из массива $webinars
     * Данных нет, всегда 0.
     */
    private function getVisitedWebinarsCount($webinars): int
    {
        return 0;
    }

    /**
     * Количество чиитателей
     */
    private function getReadersCount(int $publications, int $webinars): int
    {
        $visitedPublications = $this->getVisitedPublicationsCount($publications);
        $visitedWebinars = $this->getVisitedWebinarsCount($webinars);

        return $visitedPublications + $visitedWebinars;
    }

    /**
     * Возвращает процент активных читателей
     * @return int
     */
    private function getActiveReadersCount(int $allReaders, int $countFeedbacks): int
    {
        if ($allReaders == 0) {
            return 0;
        }
        $activeReaders = round($countFeedbacks * 100 / $allReaders);
        return $activeReaders > 100 ? 100 : $activeReaders;
    }

    /**
     * Возвращает массив с рекомендациями по публикациям и вебинарам
     * @return array
     */
    public function getRecommendation(): array
    {
        $feedbacks = $this->getFeedbacks();
        $countFeedbacks = count($feedbacks);

        $publications = $this->getPublicationList();
        $webinars = $this->getWebinarList();

        // Данных по просмотрам вебинаров НЕТ! getVisitedWebinarsCount возвращает 0.
        $allReaders = $this->getReadersCount($publications, $webinars);
        $activeReaders = $this->getActiveReadersCount($allReaders, $countFeedbacks);

        return  [
            'readers' => $allReaders,
            'activeReaders' => $activeReaders,
        ] + $this->getMaterialsStat($feedbacks);
    }
}
