<?php

namespace App\Http\Controllers;

use App\Helper\PseudoCrypt;
use App\Models\Article;
use App\Models\Community;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Category;
use App\Models\Knowledge\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class KnowledgeController extends Controller
{
    public $perPage = 15;
    private $community_id;

    public function public($hash)
    {
        $community = Community::findOrFail((int)PseudoCrypt::unhash($hash));
        $this->community_id = $community->id;

        $questions = Question::with('answer', 'category', 'community')
            ->orderBy('id')
            ->where('community_id', $community->id)
            ->get();
        $categories = Category::where('community_id', $this->community_id)->orderBy('id', 'asc')->get();
        $community->load('owner');
        return view('common.knowledge.public-list')
            ->withCommunity($community)
            ->withCategories($categories)
            ->withQuestions($questions);
    }

public function list(Request $request, Community $community)
    {
//        $community = Community::findOrFail((int)PseudoCrypt::unhash($hash));
//        $community = Community::find(461);

    $this->community_id = $community->id;

    if ($request->has('search') && $request['search'] !== null) {
        $questions = Question::where('community_id', $this->community_id)
            ->paginate($this->perPage);
    } else {
        $questions = Question::with('answer', 'category', 'community')
            ->orderBy('id')
            ->where('community_id', $community->id)
            ->paginate($this->perPage);
    }
    $categories = Category::where('community_id', $this->community_id)->orderBy('id', 'asc')->get();
    $community->load('owner');
    return view('common.knowledge.list')
        ->withCommunity($community)
        ->withCategories($categories)
        ->withQuestions($questions);
}

    public function processCategory(Request $request, Community $community)
{
    switch ($request->command) {
        case 'add':
            $cat = new Category;
            $cat->community_id = $community->id;
            $cat->variant = 'users';
            $cat->title = $request->title;
            $cat->save();
            break;
        case 'edit':
            $cat = Category::find($request->category_id);
            $cat->community_id = $community->id;
            $cat->title = $request->title;
            $cat->save();
            break;
        case 'del':
            $cat = Category::find($request->category_id);
            $cat->delete();
            break;
    }

}

    public function processKnowledge(Request $request, Community $community)
{

    switch ($request->command) {
        case 'add':
            $question = Question::createOrUpdate(['id'=>$request->question_id],
                [
                'community_id' => $community->id,
                'author_id' => Auth::user()->id,
                'uri_hash' => Str::random(32),
                'is_draft' => 1,
                'is_public' => 1,
                'c_enquiry' => 0,
                'context' => $request->vopros,
                'category_id' => $request->category_id
            ]);

            Answer::createOrUpdate(
                ['question_id' => $question->id],
                [
                'community_id' => $community->id,
                'is_draft' => false,
                'context' => $request->otvet,
            ]);
            break;
        case 'edit':
            break;
        case 'del':
            $question = Question::find($request->question_id);
            $answer = $question->answer();
            $question->delete();
            $answer->delete();
            break;
    }
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
