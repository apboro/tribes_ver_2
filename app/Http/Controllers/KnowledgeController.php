<?php

namespace App\Http\Controllers;

use App\Filters\QuestionFilter;
use App\Helper\PseudoCrypt;
use App\Models\Article;
use App\Models\Community;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KnowledgeController extends Controller
{
    public $perPage = 15;
    private $community_id;

    public function list(Request $request, QuestionFilter $filter, $hash)
    {
        $community = Community::find((int)PseudoCrypt::unhash($hash));
        $this->community_id = $community->id;

        if ($request->has('search') && $request['search'] !== null) {
            $questions = Question::filter($filter)
                ->where('community_id', $this->community_id)
                ->paginate($this->perPage);
        } else {
            $questions = Question::with('answer')
                ->orderBy('id')
                ->where('community_id', $community->id)
                ->paginate($this->perPage);
        }

        $community->load('owner');

        return view('common.knowledge.list')
            ->withCommunity($community)
            ->withQuestions($questions);
    }

    public function get(Request $request, $hash, Question $question)
    {
        $community = Community::findOrFail((int)PseudoCrypt::unhash($hash));
        Cache::forget($community->hash);

        if (!Cache::has('user_ip') || !Cache::has($question->id) || $request->ip() != Cache::get('user_ip')) {
            $question->increment('c_enquiry');
            Cache::put('user_ip', $request->ip(), 900);
            Cache::put($question->id, $question->uri_hash, 900);
        }

        $question->load('answer');

        return view('common.knowledge.get')->withCommunity($community)->withQuestion($question);
    }

    public function help($hash)
    {
        $community = Community::findOrFail((int)PseudoCrypt::unhash($hash));
        return view('common.knowledge.help')->withCommunity($community);
    }

    public function add(Request $request, Community $community)
    {
        return view('common.knowledge.form')->withCommunity($community);
    }

    public function edit(Request $request, Community $community, Question $question)
    {
        $answer = $question->answer()->first();
        $answer->load('questions');
        return view('common.knowledge.form')
            ->withCommunity($community)
            ->withAnswer($answer);
    }

    public function settings(Request $request, Community $community)
    {
        return view('common.knowledge.settings')->withCommunity($community);
    }

    public function store(Request $request, Community $community)
    {
        $answer = Answer::findOrNew($request['id']);
        $answer->title = $request['answer'];
        $answer->community_id = $community->id;
        $answer->save();

        $answer->questions()->delete();

        foreach ($request['questions'] as $q) {
            if ($q['title'] !== null) {
                $question = new Question();
                $question->title = $q['title'];
                $question->community_id = $community->id;
                $question->answer_id = $answer->id;
                $question->save();
            }
        }

        return redirect()->route('knowledge.list', $community)
            ->withMessage(__('knowledge.knowledge_form_success'));
    }
}
