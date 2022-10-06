<?php

namespace App\Repositories\Project;

use App\Filters\API\ProjectFilter;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryContract
{
    public function getUserProjectsList(int $userId, ProjectFilter $filter): Collection;

    public function getUserCommunitiesWithoutProjectList(int $userId): Collection;

    public function getProject(int $projectId, array $filter = []):?Project;

    public function create(array $attributes): ?Project;

    /**
     * @param int $projectId
     * @param array $attributes
     * @param array $filter дополнительная проверка того что в модели,
     *                      есть какие-то поля заполненные определенным образом
     *                      например ['user_id' => $owner_user_id]
     * @return Project|null
     */
    public function update(int $projectId, array $attributes, array $filter = []): ?Project;

    public function delete(int $projectId): bool;

    public function reAttachCommunities(int $projectId, array $communityIds): bool;

}