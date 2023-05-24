<?php


namespace App\Http\Controllers\APIv3\Knowledge;

use App\Http\ApiRequests\Knowledge\ApiKnowledgeBindToCommunityRequest;
use App\Http\ApiRequests\Knowledge\ApiKnowledgeDeleteRequest;
use App\Http\ApiRequests\Knowledge\ApiKnowledgeListRequest;
use App\Http\ApiRequests\Knowledge\ApiKnowledgeShowRequest;
use App\Http\ApiRequests\Knowledge\ApiKnowledgeStoreRequest;
use App\Http\ApiRequests\Knowledge\ApiKnowledgeUpdateRequest;
use App\Http\ApiResources\Knowledge\ApiKnowledgeCollection;
use App\Http\ApiResources\Knowledge\ApiKnowledgeResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Knowledge\ApiKnowledgeRepository;

class ApiKnowledgeController extends Controller
{
    protected ApiKnowledgeRepository $apiKnowledgeRepository;

    public function __construct(ApiKnowledgeRepository $apiKnowledgeRepository)
    {
        $this->apiKnowledgeRepository = $apiKnowledgeRepository;
    }

    public function store(ApiKnowledgeStoreRequest $request): ApiResponse
    {
        $knowledge = $this->apiKnowledgeRepository->add($request);

        if (!$knowledge) {
            return ApiResponse::error('Не удалось сохранить базу знаний');
        }

        return ApiResponse::common(ApiKnowledgeResource::make($knowledge)->toArray($request));
    }

    public function show(ApiKnowledgeShowRequest $request, int $id): ApiResponse
    {
        $knowledge = $this->apiKnowledgeRepository->show($id);

        if (!$knowledge) {
            return ApiResponse::error('Не удалось вывести базу знаний');
        }

        return ApiResponse::common(ApiKnowledgeResource::make($knowledge)->toArray($request));
    }

    public function update(ApiKnowledgeUpdateRequest $request, int $id)
    {
        $knowledge = $this->apiKnowledgeRepository->update($request, $id);

        if (!$knowledge) {
            return ApiResponse::error('Не удалось вывести базу знаний');
        }

        return ApiResponse::common(ApiKnowledgeResource::make($knowledge)->toArray($request));
    }

    public function list(ApiKnowledgeListRequest $request): ApiResponse
    {
        $knowledge = $this->apiKnowledgeRepository->list();

        if (!$knowledge) {
            return ApiResponse::error('Не удалось вывести базу знаний');
        }

        return ApiResponse::list()->items(ApiKnowledgeCollection::make($knowledge)->toArray($request));
    }

    public function delete(ApiKnowledgeDeleteRequest $request, int $id): ApiResponse
    {
        $isSuccess = $this->apiKnowledgeRepository->delete($id);

        if (!$isSuccess) {
            return ApiResponse::error('Не удалось удалить базу знаний');
        }

        return ApiResponse::success('База знаний успешно удалена');
    }

    public function bindToCommunity(ApiKnowledgeBindToCommunityRequest $request): ApiResponse
    {
        $isSuccess = $this->apiKnowledgeRepository->bindToCommunity($request);

        if (!$isSuccess) {
            return ApiResponse::error('Не удалось привязать базу знаний к сообществам');
        }

        return ApiResponse::success('База знаний успешно привязана к сообществам');
    }
}