<?php


namespace App\Filters;

use Elasticsearch\Client;
use App\Models\Knowledge\Question;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class QuestionFilter extends QueryFilter
{
    private $elasticsearch;

    public function __construct(Request $request, Client $elasticsearch)
    {
        parent::__construct($request);
        $this->elasticsearch = $elasticsearch;
    }

    public function search($query)
    {
        try {
            $questions = $this->buildCollection($this->searchOnElasticsearch($query, new Question));
        } catch (NoNodesAvailableException $e) {
            $questions = $this->fullTextSearch($query);
        }

        return $questions;
    }

    private function fullTextSearch($query)
    {
        return $this->builder
            ->where([
                ['context', 'like', "%{$query}%"],
                ['is_draft', false],
                ['is_public', true]
            ]);
    }

    private function searchOnElasticsearch($query = null, $model)
    {
        return $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['context'],
                        'fuzziness' => 'AUTO',
                        'query' => $query,
                    ],
                ],
            ],
        ]);

    }

    private function buildCollection(array $items)
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return $this->builder = Question::whereIn('id', $ids)
            ->where([
                ['is_draft', false],
                ['is_public', true],
            ]);//findMany($ids)->paginate($this->perPage)

    }
}
