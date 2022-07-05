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
    private $follower_map = ['', 'К', 'M', 'B'];


    public function list(Request $request, QuestionFilter $filter, $hash)
    {
        $community = Community::find((int)PseudoCrypt::unhash($hash))->with('owner');
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
        return view('common.knowledge.list')
            ->withCommunity($community)
            ->withOwner(User::find($community->owner))
            ->withCountFollowers($this->modifiedCountFollowers($community))
            ->withPopularQuestions($this->popularQuestions($community))
            ->withQuestions($questions);
    }

    public function get(Request $request, $hash, Question $question)
    {
//        $community = Community::findOrFail((int)PseudoCrypt::unhash($hash));
        $community = Community::where('hash', $hash)->firstOrFail();
        Cache::forget($community->hash);

        if (!Cache::has('user_ip') || !Cache::has($question->id) || $request->ip() != Cache::get('user_ip')) {
            $question->c_enquiry += 1;
            $question->save();
            Cache::put('user_ip', $request->ip(), 900);
            Cache::put($question->id, $community->hash, 900);
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

    private function modifiedCountFollowers($community)
    {
        $countFollowers = $community->followers()->count();
        $string = $this->getString($countFollowers);

        for ($rank = 0; $countFollowers > 999; $rank++) {
            $countFollowers = round($countFollowers / 1000, 1);
        }

        return $countFollowers . $this->follower_map[$rank] . $string;
    }

    private function getString($number)
    {
        $string = ' подписчиков';

        if ($number === 1) {
            $string = ' подписчик';
        } elseif ($number > 1 && $number < 5) {
            $string = ' подписчика';
        }

        return $string;
    }

    private function popularQuestions($community)
    {
        if ($community->questions()->count() === 0) {
            return null;
        }

        return Question::where([
            ['community_id', $this->community_id],
            ['is_public', true],
            ['is_draft', false]
        ])->orderBy('c_enquiry', 'DESC')
            ->limit(20)
            ->get()
            ->shuffle()
            ->slice(0, 5);
    }
}
