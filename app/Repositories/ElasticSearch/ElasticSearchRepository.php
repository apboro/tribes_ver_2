<?php

namespace App\Repositories\Elasticsearch;

class ElasticsearchRepository
{
    private $elasticsearch;
    public function __construct(Elasticsearch\Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }
    public function search(string $query = ''): Collection
    {
        $items = $this->searchOnElasticsearch($query);
        return $this->buildCollection($items);
    }
    private function searchOnElasticsearch(string $query = ''): array
    {
        $model = new Article;
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title^5', 'body', 'tags'],
                        'query' => $query,
                    ],
                ],
            ],
        ]);
        return $items;
    }
    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return Article::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }
}