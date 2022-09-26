<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ApiException;
use App\Filters\API\ProjectFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProjectCreateRequest;
use App\Http\Requests\API\ProjectEditRequest;
use App\Http\Requests\API\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectsResource;
use App\Models\Project;
use App\Repositories\Project\ProjectRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{

    private ProjectRepositoryContract $projectRepository;

    public function __construct(ProjectRepositoryContract $projectRepository)
    {

        $this->projectRepository = $projectRepository;
    }

    public function list(ProjectRequest $request, ProjectFilter $filter)
    {
        $collection = $this->projectRepository->getUserProjectsList(Auth::user()->id, $filter);
        return (new ProjectsResource($collection))->forApi();
    }

    public function add(ProjectCreateRequest $request)
    {
        $project = $this->projectRepository->create([
            'user_id' => Auth::user()->id,
            'title' => $request->get('title'),
        ]);

        if ($project) {
            return new ProjectResource($project);
        }

        throw new ApiException('Ошибка сохранения проекта');
    }

    public function get(ProjectRequest $request)
    {
        $project = null;
        if ($pid = $request->get('id')) {
            $project = $this->projectRepository->getProject($pid, ['user_id' => Auth::user()->id]);
        }
        if (empty ($project)) {
            throw ValidationException::withMessages([
                'id' => ["Такой проект №{$request->id} отсутствует в системе "],
            ]);
        }

        return new ProjectResource($project);
    }

    public function store(ProjectEditRequest $request)
    {
        if ($pid = $request->get('id')) {
            $project = $this->projectRepository->update(
                $pid,
                ['title' => $request->get('title')],
                ['user_id' => Auth::user()->id]
            );
            if($project) {
                return new ProjectResource($project);
            }
        }
        throw new ApiException('Ошибка сохранения проекта');
    }

    public function delete(ProjectRequest $request)
    {
        $project = Project::where('id','=',$request->get('id'))
        ->where('user_id', '=', Auth::user()->id)->get()->first();
        if (empty ($project)) {
            throw ValidationException::withMessages([
                'id' => ["Такой проект №{$request->id} отсутствует в системе "],
            ]);
        }
        $this->projectRepository->delete($project->id);
        return response()->json([
            'status' => 'ok',
            'message' => "проект №{$request->id} удален",
        ], 200);
    }
}
