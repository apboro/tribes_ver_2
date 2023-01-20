<?php

namespace App\Http\Controllers\API;

use App\Exceptions\KnowledgeException;
use App\Filters\API\QuestionsFilter;
use App\Helper\ArrayHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateQuestionRequest;
use App\Http\Requests\API\DoRequest;
use App\Http\Requests\API\QuestionRequest;
use App\Http\Requests\API\QuestionsRequest;
use App\Http\Requests\API\UpdateQuestionRequest;
use App\Http\Resources\Knowledge\QuestionResource;
use App\Http\Resources\Knowledge\QuestionsResource;
use App\Models\Knowledge\Question;
use App\Repositories\Knowledge\KnowledgeRepositoryContract;
use App\Services\Knowledge\ManageQuestionService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    private ManageQuestionService $manageQuestionService;
    private KnowledgeRepositoryContract $knowledgeRepository;

    public function __construct(
        ManageQuestionService       $manageQuestionService,
        KnowledgeRepositoryContract $knowledgeRepository
    )
    {
        $this->manageQuestionService = $manageQuestionService;
        $this->knowledgeRepository = $knowledgeRepository;
    }

    public function list(Request $request, QuestionsFilter $filters)
    {
//        $models = $this->knowledgeRepository->getQuestionsByCommunityId($questionRequest->community_id, $filters);
//
//        return (new QuestionsResource($models))->forApi();
        return Question::where('category_id', $request->category_id)->get();
    }

    public function get(QuestionRequest $questionRequest): QuestionResource
    {
        $question = $this->knowledgeRepository->getQuestionById($questionRequest->id
        );

        return new QuestionResource($question);
    }

    public function add(CreateQuestionRequest $questionRequest): JsonResponse
    {

        try {
            DB::beginTransaction();
            $this->manageQuestionService->createFromArray($questionRequest->all());
            DB::commit();
        } catch (KnowledgeException $e) {
            DB::rollBack();
            return new JsonResponse(
                [
                    'success' => false,
                    'errors' => $e->getContext(),
                    'message' => $e->getMessage(),
                ],
                $e->getCode(), [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        }
        $question = $this->knowledgeRepository->getQuestionById($this->manageQuestionService->getStoredKey());
        return new JsonResponse(
            [
                'success' => true,
                'item' => new QuestionResource($question),
                'message' => 'Объект успешно сохранен',
            ],
            200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

    }

    public function store(UpdateQuestionRequest $questionRequest): JsonResponse
    {

        try {
            DB::beginTransaction();
            $this->manageQuestionService->updateFromArray($questionRequest->all());
            DB::commit();
        } catch (KnowledgeException $e) {
            DB::rollBack();
            return new JsonResponse(
                [
                    'success' => false,
                    'errors' => $e->getContext(),
                    'message' => $e->getMessage(),
                ],
                $e->getCode(), [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        }

        $question = $this->knowledgeRepository->getQuestionById($this->manageQuestionService->getStoredKey());

        return new JsonResponse(
            [
                'success' => true,
                'item' => new QuestionResource($question),
                'message' => 'Объект успешно сохранен',
            ],
            200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

    }

    public function delete(QuestionRequest $request): JsonResponse
    {
        $result = $this->knowledgeRepository->deleteQuestion($request->get('id'));
        if ($result) {
            return new JsonResponse(
                [
                    'success' => true,
                    'item' => ['id' => $request->get('id')],
                    'message' => 'Объект успешно удален',
                ],
                200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        } else {
            return new JsonResponse(
                [
                    'success' => false,
                    'item' => ['id' => $request->get('id')],
                    'message' => 'Объект отсутствует в базе данных',
                ],
                200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        }
    }

    public function do(DoRequest $request): JsonResponse
    {
        $data = $request->all();
        $command = ArrayHelper::getValue($data, 'command', '');
        $ids = ArrayHelper::getValue($data, 'ids', []);
        $mark = ArrayHelper::getValue($data, 'params.mark', false);

        if ($this->manageQuestionService->massOperation($command, $ids, $mark)) {
            return new JsonResponse(
                [
                    'success' => true,
                    'message' => 'Операция успешна',
                ],
                200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        } else {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Операция завершилась с ошибками',
                    'items' => $this->knowledgeRepository->getQuestionsByIds($this->manageQuestionService->getWrongIds()),
                    'errors' => $this->manageQuestionService->getErrors(),
                ],
                200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        }
    }
}
