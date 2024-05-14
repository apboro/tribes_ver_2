<?php

namespace App\Http\Controllers\APIv3\Shop;

use App\Http\ApiRequests\Shop\ApiStoryShowRequest;
use App\Http\ApiResources\StoryResourse;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;

class ApiStoriesController extends Controller
{

    public function list(Request $request): ApiResponse
    {
        $story = Story::getAll();

        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => count($story)
            ]
        )->items((StoryResourse::collection($story))->toArray($request));
    }

    public function show(ApiStoryShowRequest $request, $id): ApiResponse
    {
        $story = Story::find($id);

        return ApiResponse::common(StoryResourse::make($story)->toArray($request));
    }
}