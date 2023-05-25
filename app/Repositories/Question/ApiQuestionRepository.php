<?php


namespace App\Repositories\Question;

use App\Http\ApiRequests\Question\ApiQuestionListRequest;
use App\Http\ApiRequests\Question\ApiQuestionStoreRequest;
use App\Http\ApiRequests\Question\ApiQuestionUpdateRequest;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiQuestionRepository
{
    const TYPE_IMAGE_QUESTION = 'question_image';
    const TYPE_IMAGE_ANSWER = 'answer_image';

    public function add(ApiQuestionStoreRequest $request)
    {
        /** @var Answer $answer */
        $answer = Answer::query()->create([
            'context' => $request->get('answer_text'),
            'image' => $request->get('answer_image'),
        ]);

        if (!$request->get('answer_image') === null) {
            $this->uploadAnswer($request, $answer, self::TYPE_IMAGE_ANSWER);
        }

        if (!$answer) {
            return false;
        }

        /** @var Question $question */
        $question = Question::query()->create([
            'status' => $request->get('question_status'),
            'knowledge_id' => $request->get('knowledge_id'),
            'category_id' => $request->get('category_id'),
            'context' => $request->get('question_text'),
            'answer_id' => $answer->id,
            'author_id' => Auth::user()->id,
            'image' => $request->get('question_image'),
        ]);


        if (!$question) {
            $answer->delete();
            return false;
        }

        if (!$request->has('question_image') === null) {
            $this->uploadQuestion($request, $question, self::TYPE_IMAGE_QUESTION);
        }

        $question->knowledge->touch();

        return $question;
    }

    public function list(ApiQuestionListRequest $request, $id)
    {
        /** @var Question $question */
        $questions = Question::query()
            ->where('author_id', Auth::user()->id)
            ->where('knowledge_id', $id)
            ->when($request->get('category_id'), function (Builder $query) use ($request) {
                $query->where('category_id', '=', $request->get('category_id'));
            })
            ->when($request->get('question_status'), function (Builder $query) use ($request) {
                $query->where('status', 'like', $request->get('question_status'));
            })
            ->get();

        if (!$questions) {
            return false;
        }

        return $questions;
    }

    public function show(int $id)
    {
        /** @var Question $question */
        $question = Question::query()
            ->where('id', $id)
            ->where('author_id', Auth::user()->id)
            ->first();

        if (!$question) {
            return false;
        }

        return $question;
    }

    public function update(ApiQuestionUpdateRequest $request, int $id)
    {
        /** @var Question $question */
        $question = Question::query()
            ->where('id', $id)
            ->where('author_id', Auth::user()->id)
            ->first();

        if (!$question) {
            return false;
        }

        $question->answer->update([
            'context' => $request->get('answer_text')
        ]);

        $question->update([
            'status' => $request->get('question_status'),
            'category_id' => $request->get('category_id'),
            'context' => $request->get('question_text'),
        ]);

        $question->knowledge->touch();

        return $question;
    }

    public function delete(int $id)
    {
        /** @var Question $question */
        $question = Question::query()
            ->where('author_id', Auth::user()->id)
            ->where('id', $id)
            ->first();

        if (!$question) {
            return false;
        }

        return $question->delete();
    }

    public function uploadAnswer(ApiQuestionStoreRequest $request, Answer $answer, string $type)
    {
        $file = $request->file($type);
        $upload_folder = 'public/answers';
        $extension = $file->getClientOriginalExtension();
        $filename = md5(rand(1, 1000000) . $file->getClientOriginalName() . time()) . '.' . $extension;
        Storage::putFileAs($upload_folder, $file, $filename);
        $answer->image = 'storage/app/' . $upload_folder . '/' . $filename;
        $answer->save();
    }

    public function uploadQuestion(ApiQuestionStoreRequest $request, Question $question, string $type)
    {
        $file = $request->file($type);
        $upload_folder = 'public/questions';
        $extension = $file->getClientOriginalExtension();
        $filename = md5(rand(1, 1000000) . $file->getClientOriginalName() . time()) . '.' . $extension;
        Storage::putFileAs($upload_folder, $file, $filename);
        $question->image = 'storage/app/' . $upload_folder . '/' . $filename;
        $question->save();
    }
}
