<?php


namespace App\Http\Controllers\APIv3\Knowledge;

use App\Http\ApiRequests\Question\ApiQuestionDeleteRequest;
use App\Http\ApiRequests\Question\ApiQuestionListRequest;
use App\Http\ApiRequests\Question\ApiQuestionShowRequest;
use App\Http\ApiRequests\Question\ApiQuestionStoreRequest;
use App\Http\ApiRequests\Question\ApiQuestionUpdateRequest;
use App\Http\ApiResources\ApiQuestionCollection;
use App\Http\ApiResources\ApiQuestionResource;
use App\Http\ApiResponses\ApiResponse;
use App\Repositories\Question\ApiQuestionRepository;

class ApiQuestionController
{
    protected ApiQuestionRepository $apiQuestionRepository;

    public function __construct(ApiQuestionRepository $apiQuestionRepository)
    {
        $this->apiQuestionRepository = $apiQuestionRepository;
    }

    public function store(ApiQuestionStoreRequest $request)
    {
        $question = $this->apiQuestionRepository->add($request);

        if (!$question) {
            return ApiResponse::error('Не удалось сохранить вопрос');
        }

        return ApiResponse::common(ApiQuestionResource::make($question)->toArray($request));
    }

    public function list(ApiQuestionListRequest $request, int $id)
    {
        $questions = $this->apiQuestionRepository->list($request, $id);

        if (!$questions) {
            return ApiResponse::error('Не удалось сохранить вопрос');
        }

        return ApiResponse::list()->items(ApiQuestionCollection::make($questions)->toArray($request));
    }

    public function show(ApiQuestionShowRequest $request, int $id)
    {
        $question = $this->apiQuestionRepository->show($id);

        if (!$question) {
            return ApiResponse::error('Не удалось показать вопрос');
        }

        return ApiResponse::common(ApiQuestionResource::make($question)->toArray($request));
    }

    public function update(ApiQuestionUpdateRequest $request, int $id)
    {
        $question = $this->apiQuestionRepository->update($request, $id);

        if (!$question) {
            return ApiResponse::error('Не удалось обновить вопрос');
        }

        return ApiResponse::common(ApiQuestionResource::make($question)->toArray($request));
    }

    public function delete(ApiQuestionDeleteRequest $request, int $id)
    {
        $isSuccess = $this->apiQuestionRepository->delete($id);

        if (!$isSuccess) {
            return ApiResponse::error('Не удалось удалить вопрос');
        }

        return ApiResponse::success('Вопрос успешно удален');
    }
}
