<?php


namespace App\Http\Controllers\APIv3\Knowledge;


use App\Http\ApiRequests\QuestionCategory\ApiQuestionCategoryDeleteRequest;
use App\Http\ApiRequests\QuestionCategory\ApiQuestionCategoryListRequest;
use App\Http\ApiRequests\QuestionCategory\ApiQuestionCategoryShowRequest;
use App\Http\ApiRequests\QuestionCategory\ApiQuestionCategoryStoreRequest;
use App\Http\ApiRequests\QuestionCategory\ApiQuestionCategoryUpdateRequest;
use App\Http\ApiResources\ApiQuestionCategoryCollection;
use App\Http\ApiResources\ApiQuestionCategoryResource;
use App\Http\ApiResponses\ApiResponse;
use App\Repositories\QuestionCategory\ApiQuestionCategoryRepository;

class ApiQuestionCategoryController
{
    protected ApiQuestionCategoryRepository $apiQuestionCategoryRepository;

    public function __construct(ApiQuestionCategoryRepository $apiQuestionCategoryRepository)
    {
        $this->apiQuestionCategoryRepository = $apiQuestionCategoryRepository;
    }

    public function store(ApiQuestionCategoryStoreRequest $request): ApiResponse
    {
        $questionCategory = $this->apiQuestionCategoryRepository->add($request);

        if (!$questionCategory) {
            return ApiResponse::error('Не удалось создать категорию вопросов');
        }

        return ApiResponse::common(ApiQuestionCategoryResource::make($questionCategory)->toArray($request));
    }

    public function update(ApiQuestionCategoryUpdateRequest $request, int $id): ApiResponse
    {
        $questionCategory = $this->apiQuestionCategoryRepository->update($request, $id);

        if (!$questionCategory) {
            return ApiResponse::error('Не удалось обновить категорию вопросов');
        }

        return ApiResponse::common(ApiQuestionCategoryResource::make($questionCategory)->toArray($request));
    }

    public function list(ApiQuestionCategoryListRequest $request): ApiResponse
    {
        $questionCategories = $this->apiQuestionCategoryRepository->list();

        if (!$questionCategories) {
            return ApiResponse::error('Не удалось показать список категорий вопросов');
        }

        return ApiResponse::list()->items(ApiQuestionCategoryCollection::make($questionCategories)->toArray($request));
    }

    public function show(ApiQuestionCategoryShowRequest $request, int $id): ApiResponse
    {
        $questionCategory = $this->apiQuestionCategoryRepository->show($id);

        if (!$questionCategory) {
            return ApiResponse::error('Ну далось показать категорию вопросов');
        }

        return ApiResponse::common(ApiQuestionCategoryResource::make($questionCategory)->toArray($request));
    }

    public function delete(ApiQuestionCategoryDeleteRequest $request, int $id): ApiResponse
    {
        $isSuccess = $this->apiQuestionCategoryRepository->delete($id);

        if (!$isSuccess) {
            return ApiResponse::error('Не удалось удалить категорию вопросов');
        }

        return ApiResponse::success('Категория вопросов успешно удалена');
    }
}