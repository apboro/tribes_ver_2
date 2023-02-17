<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseListPagination;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseListPaginationTest extends TestCase
{
    public function test_api_response_list_pagination_code()
    {
        $response = new ApiResponseListPagination();
        $request = new Request();
        $response->items([1,2,3]);
        $result = $response->toResponse($request);
        $this->assertEquals(200,$result->status());
    }

    public function test_api_response_list_pagination_content()
    {
        $response= new ApiResponseListPagination();
        $request = new Request();
        $response->items([1,2,3]);
        $result = $response->toResponse($request);
        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'list'=>[1,2,3],
                    'message'=>null,
                    'payload' =>[],
                    'pagination'=>[
                        'current_page' =>  1,
                        'last_page' => 1,
                        'from' =>  1,
                        'to' => 3,
                        'total' => 3,
                        'per_page' => 3,
                    ]
                ]
            ),
        );
    }
}
