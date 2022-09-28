<?php

namespace App\Http\Controllers;

use App\Filters\API\ProjectFilter;
use App\Helper\ArrayHelper;
use App\Http\Requests\Project\ProjectRequest;
use App\Models\Community;
use App\Models\Project;
use App\Repositories\Project\ProjectRepositoryContract;
use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{

    private ProjectRepositoryContract $projectRepository;

    public function __construct(ProjectRepositoryContract $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function analytics($project = null, $community = null, ProjectRequest $request)
    {

        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.analytics')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }

    public function subscribers($project = null, $community = null, ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.subscribers')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }

    public function messages($project = null, $community = null,ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.messages')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }

    public function payments($project = null, $community = null,ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.payments')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }

    public function donates($project = null, $community = null,ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.analytics')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }

    public function tariffs($project = null, $community = null,ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.tariff')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }

    public function members($project = null, $community = null,ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.members')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }


    private function getAuthorProjects(ProjectRequest $request)
    {
        if (request('community') && Community::where('id', request('community'))->where('owner', Auth::user()->id)->doesntExist()) {
            abort(403, 'Доступ запрещен');
        }
        $reqProject = request('project');
        if ($reqProject && $reqProject != 'c' && Project::where('id', $reqProject)->where('user_id', Auth::user()->id)->doesntExist()) {
            abort(403, 'Доступ запрещен');
        }


        $filter = app(ProjectFilter::class);
        $projects = $this->projectRepository->getUserProjectsList(Auth::user()->id, $filter)->keyBy('id');
        $communitiesWP = $this->projectRepository->getUserCommunitiesWithoutProjectList(Auth::user()->id)->keyBy('id');
        $activeProject = $projects->get($reqProject);
        if ($activeProject) {
            $activeCommunity = $activeProject->communities()->get()->keyBy('id')->get(request('community'));
        } else {
            $activeCommunity = $communitiesWP->get(request('community'));
        }

        $ids = ($activeProject && empty($activeCommunity))
            ? ArrayHelper::getColumn($activeProject->communities()->get(), 'id')
            : (!empty($activeCommunity) ? [$activeCommunity->id] : ['all']);
        $ids = implode('-', $ids);
        if (request('community') && $activeProject && empty($activeCommunity)) {
            abort(404, 'Страница не существует');
        }


        return [$projects, $communitiesWP, $activeProject, $activeCommunity, $ids];
    }
}
