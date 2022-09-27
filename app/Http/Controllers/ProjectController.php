<?php

namespace App\Http\Controllers;

use App\Filters\API\ProjectFilter;
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

    public function analytics(ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity) = $this->getAuthorProjects($request);

        return view('common.project.analytics')->with(compact('projects', 'communities', 'activeProject', 'activeCommunity'));
    }

    public function donates(ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity) = $this->getAuthorProjects($request);

        return view('common.project.donate')->with(compact('projects', 'communities', 'activeProject', 'activeCommunity'));
    }

    public function tariffs(ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity) = $this->getAuthorProjects($request);

        return view('common.project.tariff')->with(compact('projects', 'communities', 'activeProject', 'activeCommunity'));
    }

    public function members(ProjectRequest $request)
    {
        $project = $this->getAuthorProjects($request);

        return view('common.project.members')->with(compact('project'));
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
        return [$projects, $communitiesWP, $activeProject, $activeCommunity];
    }
}
