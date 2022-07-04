<?php

namespace App\Repositories\Question;

use Elasticsearch\Common\Exceptions\NoNodesAvailableException;

/**
 * @deprecated Knowledge repository
 */
class QuestionRepository
{
    public function search(string $query = null)
    {
        if(str_ends_with($query, ']') && str_starts_with($query, '[')){
            $items = $this->searchByTag($query);
        } else {
            if (! config('services.search.enabled')) {
                $items = $items = $this->fulltextSearch($query);
            } else {
                try{
                    $items = $this->searchOnElasticsearch($query);
                    $items = $this->buildCollection($items);
                } catch (NoNodesAvailableException $e){
                    $items = $this->fulltextSearch($query);
                }
            }
        }

        return $items;
    }
}