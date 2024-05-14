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
        return view('common.story.show', ['story' => null]);
    }

    public function edit(Request $request, $id)
    {
        $story = Story::findOrFail($id);

        return view('common.story.show', ['story' => $story]);
    }

    public function store(Request $request)
    {
        $image = $request->file('image') ? Storage::disk('public')->putFile('stories', $request->file('image')) : null;
        $ico = $request->file('ico') ? Storage::disk('public')->putFile('stories', $request->file('ico')) : null;
        Story::create(['image' => $image, 'ico' => $ico] + $request->all());

        return \redirect(route('stories.index'));
    }

    public function update(Request $request, $id)
    {
        $story = Story::findOrFail($id);
        $images = [];
        if ($request->file('image')) {
            $images['image'] = Storage::disk('public')->putFile('stories', $request->file('image'));
        }
        if ($request->file('ico')) {
            $images['ico'] = Storage::disk('public')->putFile('stories', $request->file('ico'));
        }
         $story->update($images + $request->all());

        return \redirect(route('stories.index'));
    }
    
    public function imageDestroy(Request $request, $type, $id)
    {
        $story = Story::findOrFail($id);
        if ($story->{ $type }) {
            Storage::disk('public')->delete($story->{ $type });
            $story->{ $type } = null;
            $story->save();
        }

        return \redirect(route('stories.edit', $id));
    }

    public function destroy(Request $request, $id)
    {
        $story = Story::findOrFail($id);
        if ($story->image) {
            Storage::disk('public')->delete($story->image);
        }
        if ($story->ico) {
            Storage::disk('public')->delete($story->ico);
        }
        $story->delete();

        return \redirect(route('stories.index'));
    }
}