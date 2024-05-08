<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoriesController extends Controller
{

    public function index()
    {
        $stories = Story::all();

        return view('common.story.list', ['stories' => $stories]);
    }

    public function create()
    {
        return view('common.story.create');
    }

    public function store(Request $request)
    {
        $image = $request->file('image') ? Storage::disk('public')->putFile('stories', $request->file('image')) : null;
        Story::create(['image' => $image] + $request->all());

        return \redirect(route('stories.index'));
    }

    public function destroy(Request $request, $id)
    {
        $story = Story::findOrFail($id);
        if ($story->image) {
            Storage::disk('public')->delete($story->image);
        }
        $story->delete();

        return \redirect(route('stories.index'));
    }
}