<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;
use My\Service\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectController extends Controller
{
    public function analytics(Request $request)
    {
        /* Псевдокод

        $project = (int)$request->project ? Project::find((int)$request->project) : null;

        $community = (int)$request->community ? Community::find((int)$request->community) : null;

        Обязательно нужна проверка принадлежности сообщества к проекту, и на пренадлежность к авторизованому юзеру

        return view('common.project.analytic')->with(compact('project', 'всё что нужно для аналитики или тарифов...'));

        КонецПсевдокод */

        $project = $this->findProject(0);

        return view('common.project.analytic')->with(compact('project'));
    }

    public function donate(Request $request)
    {
        $project = $this->findProject(0);

        return view('common.project.donate')->with(compact('project'));
    }

    public function tariff(Request $request)
    {
        $project = $this->findProject(0);

        return view('common.project.tariff')->with(compact('project'));
    }


    private function findProject($id)
    {
        $project = new \stdClass();
        $project->id = 1;
        $project->name = 'Проект №1';

        $project->other = collect([
            (object)['id' => 1, 'name' => 'Другой проект 1', 'link' => route('project.analytic', 1)],
            (object)['id' => 2, 'name' => 'Другой проект 2', 'link' => route('project.analytic', 2)],
            (object)['id' => 3, 'name' => 'Другой проект 3', 'link' => route('project.analytic', 3)],
        ]);

        $project->communities = collect([
            (object)['id' => 1, 'name' => 'Первое сообщество'],
            (object)['id' => 2, 'name' => 'Второе сообщество'],
            (object)['id' => 3, 'name' => 'Третье сообщество'],
        ]);

        return $project;
    }
}
