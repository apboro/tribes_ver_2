<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseList;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseListTest extends TestCase
{
    public function test_api_response_list_code()
    {

        $response = new ApiResponseList();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(200, $result->status());
    }

    public function test_api_response_list_content()
    {
        $response= new ApiResponseList();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'list'=>[],
                    'message'=>null,
                    'payload' =>[]
                ]
            ),
        );
    }
}
