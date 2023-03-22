<?php

namespace App\Http\Controllers\APIv3;

use App\Filters\API\ProjectFilter;
use App\Http\ApiRequests\Project\ApiAddProjectRequest;
use App\Http\ApiRequests\Project\ApiProjectListRequest;
use App\Http\ApiRequests\Project\ApiProjectShowRequest;
use App\Http\ApiRequests\Project\ApiProjectUpdateRequest;
use App\Http\ApiResources\ProjectCollection;
use App\Http\ApiResources\ProjectResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Project\ProjectRepositoryContract;
use Illuminate\Support\Facades\Auth;

class ApiProjectController extends Controller
{
    private ProjectRepositoryContract $projectRepository;

    public function __construct(ProjectRepositoryContract $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Projects list.
     *
     * @param ApiProjectListRequest $request
     * @param ProjectFilter $filter
     *
     * @return ApiResponse
     */
    public function index(ApiProjectListRequest $request, ProjectFilter $filter): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $projects = $this->projectRepository
            ->getUserProjectsList($user->id, $filter);

        return ApiResponse::list()
            ->items(ProjectCollection::make($projects)->toArray($request));
    }

    /**
     * Show project info by ID.
     *
     * @param $id
     * @param ApiProjectShowRequest $request
     * @param ProjectFilter $filter
     *
     * @return ApiResponse
     */
    public function show($id, ApiProjectShowRequest $request, ProjectFilter $filter): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Project|null $project */
        $project = Project::query()
            ->where('id', $id)
            ->first();

        if ($project === null) {
            return ApiResponse::notFound('validation.project.not_found');
        }

        if (!$user->can('view', $project)) {
            return ApiResponse::unauthorized();
        }

        return ApiResponse::common(ProjectResource::make($project)->toArray($request));
    }

    /**
     * Create new project and attach communities.
     *
     * @param ApiAddProjectRequest $request
     *
     * @return ApiResponse
     */
    public function create(ApiAddProjectRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $project = $this->projectRepository->create([
            'user_id' => $user->id,
            'title' => $request->get('title'),
            'communities' => $request->get('communities'),
        ]);

        return ApiResponse::common(ProjectResource::make($project)->toArray($request));
    }

    /**
     * Update project.
     *
     * @param ApiProjectUpdateRequest $request
     * @param $id
     *
     * @return ApiResponse
     */
    public function update(ApiProjectUpdateRequest $request, $id): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Project $project */
        $project = Project::query()->where('id', '=', $id)->first();

        if ($project === null) {
            return ApiResponse::notFound('validation.project.not_found');
        }

        if (!$user->can('view', $project)) {
            return ApiResponse::unauthorized();
        }

        $project = $this->projectRepository->update(
            $id,
            [
                'title' => $request->input('title'),
                'communities' => $request->get('communities'),
            ],
            ['user_id' => $user->id]
        );

        if ($project === null) {
            return ApiResponse::error('common.project_update_error');
        }

        return ApiResponse::common($project);
    }

}
