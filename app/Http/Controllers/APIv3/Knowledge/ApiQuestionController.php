<?php


namespace App\Http\Controllers\APIv3\Knowledge;

use App\Http\ApiRequests\Question\ApiQuestionAiListRequest;
use App\Http\ApiRequests\Question\ApiQuestionDeleteRequest;
use App\Http\ApiRequests\Question\ApiQuestionListRequest;
use App\Http\ApiRequests\Question\ApiQuestionShowRequest;
use App\Http\ApiRequests\Question\ApiQuestionStoreRequest;
use App\Http\ApiRequests\Question\ApiQuestionUpdateRequest;
use App\Http\ApiResources\Knowledge\ApiQuestionCollection;
use App\Http\ApiResources\Knowledge\ApiQuestionResource;
use App\Http\ApiResponses\ApiResponse;
use App\Models\Knowledge\Question;
use App\Models\Knowledge\QuestionAI;
use App\Models\User;
use App\Repositories\Question\ApiQuestionRepository;
use Exception;
use Log;
use Illuminate\Http\Response;

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

    public function listAi(ApiQuestionAiListRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        $communities = $user->getOwnCommunities()->pluck('id')->toArray();

        $questions = $this->apiQuestionRepository->listAi($communities);

        if (!$questions) {
            return ApiResponse::error('Список пуст');
        }

        return response()->json($questions);
//        return ApiResponse::list()->items(ApiQuestionCollection::make($questions)->toArray($request));
    }

    public function showAi(ApiQuestionShowRequest $request, int $id)
    {
        $question = $this->apiQuestionRepository->showAi($id);

        if (!$question) {
            return ApiResponse::error('Не удалось показать вопрос');
        }

        return response()->json($question);

//        return ApiResponse::common(ApiQuestionResource::make($question)->toArray($request));
    }

    public function storeQuestionAI(ApiQuestionStoreRequest $request)
    {
        try {
            $question = $this->apiQuestionRepository->add($request);
            $questionAiId = $request->get('id', 0);

            QuestionAI::setMovedQuestionStatus($questionAiId, $question->id);

            return response()->json(['message' => 'ok']);
        } catch (Exception $exception) {
            Log::error($exception);

            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
