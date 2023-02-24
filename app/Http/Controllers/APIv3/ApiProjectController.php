<?php

namespace App\Http\Controllers\APIv3;

use App\Filters\API\ProjectFilter;
use App\Http\ApiRequests\ApiAddProjectRequest;
use App\Http\ApiRequests\ApiShowProjectRequest;
use App\Http\ApiRequests\ApiUpdateProjectRequest;
use App\Http\ApiResources\ProjectCollection;
use App\Http\ApiResources\ProjectResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;

use App\Http\Requests\API\ProjectEditRequest;
use App\Models\Project;
use App\Repositories\Project\ProjectRepositoryContract;


use Askoldex\Teletant\Api;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;

class ApiProjectController extends Controller
{

    private ProjectRepositoryContract $projectRepository;

    public function __construct(
        ProjectRepositoryContract $projectRepository
    )
    {
        $this->projectRepository = $projectRepository;
    }

    public function index(ProjectFilter $filter){
        $projects = $this->projectRepository->getUserProjectsList(Auth::user()->id, $filter)->keyBy('id');
        return ApiResponse::common([
            'data'=>new ProjectCollection($projects),
        ]);
    }


    public function show($id, ApiShowProjectRequest $request): ApiResponse
    {
        $project = Project::find($id);
        if (empty($project)) {
            return ApiResponse::notFound('validation.project.not_found');
        }
        if(!Auth::user()->can('view',$project)){
            return ApiResponse::unauthorized();
        }
        return ApiResponse::common(['project' => new ProjectResource($project)]);
    }

    public function create(ApiAddProjectRequest $request): ApiResponse
    {
        $project = $this->projectRepository->create([
            'user_id' => Auth::user()->id,
            'title' => $request->get('title'),
            'communities' => $request->get('communities'),
        ]);
        return ApiResponse::common(ProjectResource::make($project)->toArray($request));
    }

    public function update(ApiUpdateProjectRequest $request, $id)
    {
        $project_data = Project::where('id', '=', $id)->first();
        if (empty($project_data)) {
            return ApiResponse::notFound('validation.project.not_found');
        }

        if(!Auth::user()->can('view',$project_data)){
            return ApiResponse::unauthorized();
        }
        $project = $this->projectRepository->update(
            $id,
            [
                'title' => $request->input('title'),
                'communities' => $request->get('communities'),
            ],
            ['user_id' => Auth::user()->id]
        );
        if (empty($project)) {
            return ApiResponse::error('common.project_update_error');
        }
        return ApiResponse::common(['project' => $project]);
    }


}
