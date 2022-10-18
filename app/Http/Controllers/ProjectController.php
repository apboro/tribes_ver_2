<?php

namespace App\Http\Controllers;

use App\Filters\API\ProjectFilter;
use App\Filters\TariffFilter;
use App\Helper\ArrayHelper;
use App\Http\Requests\Project\ProjectEditRequest;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectRequest;
use App\Models\Community;
use App\Models\Project;
use App\Repositories\Donate\DonateRepositoryContract;
use App\Repositories\Project\ProjectRepositoryContract;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{

    private ProjectRepositoryContract $projectRepository;
    private DonateRepositoryContract $donateRepository;
    private TariffRepositoryContract $tariffRepository;

    public function __construct(
        ProjectRepositoryContract $projectRepository,
        DonateRepositoryContract  $donateRepository,
        TariffRepositoryContract  $tariffRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->donateRepository = $donateRepository;
        $this->tariffRepository = $tariffRepository;
    }

    public function listProjects(ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        if (!Auth::user()->hasCommunities()) {
            return redirect()->route('profile.communities.list');
        }

        return view('common.project.list')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity')
        );
    }

    public function listCommunities(ProjectRequest $request)
    {
        list($projects, $communitiesWP, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);
        $communities = $this->projectRepository->getUserCommunitiesList(Auth::user()->id)->keyBy('id');
        return view('common.project.communities')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity')
        );
    }

    public function add(ProjectCreateRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);
        $project = new Project();
        if ($request->isMethod('post')) {

            $project = $this->projectRepository->create([
                'user_id' => Auth::user()->id,
                'title' => $request->get('title'),
                'communities' => $request->get('communities'),
            ]);
            return redirect()->route('profile.project.list')->withMessage('Сохранено');
        }
        return view('common.project.add')->with(
            compact('project', 'communities', 'request')
        );
    }

    public function edit(Project $project, ProjectRequest $request)
    {
        if ($project->user_id !== Auth::user()->id) {
            abort('403', 'Доступ запрещен');
        }
        $communities = $this->projectRepository->getUserCommunitiesWithoutProjectList(Auth::user()->id)->keyBy('id');
        if ($request->isMethod('post')) {
            $requestUpdate = app()->make(ProjectEditRequest::class);
            $project = $this->projectRepository->update(
                $project->id,
                [
                    'title' => $requestUpdate->get('title'),
                    'communities' => $request->get('communities'),
                ],
                ['user_id' => Auth::user()->id]
            );
            if (empty($project)) {
                return view('common.project.edit')->with(
                    compact('project', 'communities', 'requestUpdate')
                );
            }
            return redirect()->route('profile.project.list')->withMessage('Сохранено');
        }
        return view('common.project.edit')->with(
            compact('project', 'communities')
        );
    }

    public function analytics($project = null, $community = null, ProjectRequest $request)
    {

        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        if ($project === null && $projects->isNotEmpty()) {
            return redirect()->route('project.analytics', ['project' => $projects->first()]);
        }
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

    public function messages($project = null, $community = null, ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.messages')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }

    public function payments($project = null, $community = null, ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        return view('common.project.payments')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community')
        );
    }

    public function donates($project = null, $community = null, ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);

        if ($project === null && $projects->isNotEmpty()) {
            return redirect()->route('project.donates', ['project' => $projects->first()]);
        }
        $donates = empty($ids) ? null : $this->donateRepository->getDonatesByCommunities(explode('-', $ids));

        return view('common.project.donate')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community', 'donates')
        );
    }

    public function tariffs($project = null, $community = null, ProjectRequest $request)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);
        if ($project === null && $projects->isNotEmpty()) {
            return redirect()->route('project.tariffs', ['project' => $projects->first()]);
        }
        if ($request->get('isPersonal')) {
            $isPersonal = true;
            $isActive = true;
        } elseif ($request->get('active', "true") == "true") {
            $isPersonal = false;
            $isActive = true;
        } else {
            $isActive = false;
            $isPersonal = null;
        }
        $tariffs = empty($ids) ? null : $this->tariffRepository->getTariffVariantsByCommunities(explode('-', $ids), $isActive, $isPersonal);

        return view('common.project.tariff')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity',
                'ids', 'project', 'community', 'tariffs', 'isPersonal', 'isActive')
        );
    }

    public function members($project = null, $community = null, ProjectRequest $request, TariffFilter $filters)
    {
        list($projects, $communities, $activeProject, $activeCommunity, $ids) = $this->getAuthorProjects($request);
        $followers = $this->tariffRepository->getList($filters, $activeCommunity);
        //dd($activeCommunity);
        return view('common.project.members')->with(
            compact('projects', 'communities', 'activeProject', 'activeCommunity', 'ids', 'project', 'community', 'followers')
        );
    }

    /**
     * @param $request
     * @return array
     */
    private function getAuthorProjects($request)
    {
        if (request('community') && Community::where('id', request('community'))->where('owner', Auth::user()->id)->doesntExist()) {
            abort(403, 'Доступ запрещен');
        }
        $reqProject = request('project');
        if ($reqProject && ctype_digit($reqProject) && Project::where('id', $reqProject)->where('user_id', Auth::user()->id)->doesntExist()) {
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
        // todo set last $activeProject $activeCommunity pair into session for backlinks and redirect after save actions
        session(['activeCommunity' => $activeCommunity->id ?? null, 'activeProject' => $activeProject->id ?? null]);

        return [$projects, $communitiesWP, $activeProject, $activeCommunity, $ids];
    }
}
