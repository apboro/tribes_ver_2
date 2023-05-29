<?php


namespace App\Repositories\Question;

use App\Http\ApiRequests\ApiRequest;
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
        ]);

        if (!empty($request->file('answer_image'))) {
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
        ]);


        if (!$question) {
            $answer->delete();
            return false;
        }

        if (!empty($request->file('question_image'))) {
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
            'context' => $request->get('question_text'),
            'category_id' => $request->get('category_id'),
        ]);

        if (!empty($request->file('question_image'))) {
            $this->uploadQuestion($request, $question, self::TYPE_IMAGE_QUESTION);
        } else {
            $question->update(['image'=>null]);
        }

        if (!empty($request->file('answer_image'))) {
            $this->uploadAnswer($request, $question->answer, self::TYPE_IMAGE_ANSWER);
        } else {
            $question->answer->update(['image'=>null]);
        }

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

    public function uploadAnswer(ApiRequest $request, Answer $answer, string $type)
    {
        $file = $request->file($type);
        $upload_folder = 'public/questions_images';
        $extension = $file->getClientOriginalExtension();
        $filename = md5(rand(1, 1000000) . $file->getClientOriginalName() . time()) . '.' . $extension;
        Storage::putFileAs($upload_folder, $file, $filename);
        $answer->image = 'storage/questions_images/' . $filename;
        $answer->save();
    }

    public function uploadQuestion(ApiRequest $request, Question $question, string $type)
    {
        $file = $request->file($type);
        $upload_folder = 'public/questions_images';
        $extension = $file->getClientOriginalExtension();
        $filename = md5(rand(1, 1000000) . $file->getClientOriginalName() . time()) . '.' . $extension;
        Storage::putFileAs($upload_folder, $file, $filename);
        $question->image = 'storage/questions_images/' . $filename;
        $question->save();
    }
}
