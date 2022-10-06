<?php

namespace App\Repositories\Project;

use App\Exceptions\ApiException;
use App\Filters\API\ProjectFilter;
use App\Helper\ArrayHelper;
use App\Models\Community;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProjectRepository implements ProjectRepositoryContract
{

    public function getUserProjectsList(int $userId, ProjectFilter $filter): Collection
    {
        $filter->replace(['user_id' => $userId]);
        return Project::filter($filter)->with('communities')->get();
           /* ->paginate(ArrayHelper::getValue($filter->filters(), 'per_page', 50),
                ['*'],
                'page',
                ArrayHelper::getValue($filter->filters(), 'page', 1));*/
    }

    public function getProject(int $projectId, array $filter = []): ?Project
    {
        $builder = Project::where('id', '=', $projectId);
        if (!empty($filter)) {
            foreach ($filter as $key => $value) {
                $builder->where($key, '=', $value);
            }
        }
        return $builder->get()->first();
    }

    public function create(array $attributes): ?Project
    {
        $project = new Project();
        $project->fill($attributes);
        if (!$project->save()) {
            return null;
        }
        return $project;
    }

    public function update(int $projectId, array $attributes, array $filter = []): ?Project
    {
        $project = Project::find($projectId);
        if (empty($project)) {
            return null;
        }
        if (!empty($filter)) {
            foreach ($filter as $key => $value) {
                if ($project->{$key} !== $value) {
                    return null;
                }
            }
        }
        $project->fill($attributes);
        if (!$project->save()) {
            return null;
        }
        return $project;
    }

    public function delete(int $projectId): bool
    {
        if ($project = Project::find($projectId)) {
            DB::transaction(function () use ($project) {
                Community::where('project_id', '=', $project->id)
                    ->update(['project_id' => null]);
                $project->delete();
            });
            return true;
        }
        return false;
    }

    public function reAttachCommunities(int $projectId, array $communityIds): bool
    {
        if (Project::where(['id' => $projectId])->doesntExist()) {
            throw new ApiException('Попытка привязать сообщества к несуществующему проекту');
        }
        if(empty($communityIds)) {
            Community::where('project_id','=',$projectId)->update(['project_id' => null]);
        } else {
            Community::where('project_id','=',$projectId)->whereNotIn('id',$communityIds)->update(['project_id' => null]);
            Community::whereIn('id', $communityIds)->update(['project_id' => $projectId]);
        }

        return true;
    }

    public function getUserCommunitiesWithoutProjectList(int $userId): Collection
    {
        return Community::where('owner','=',$userId)->whereNull('project_id')->get();
    }
}