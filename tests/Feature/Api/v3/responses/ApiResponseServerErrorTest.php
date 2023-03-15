<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseServerError;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseServerErrorTest extends TestCase
{
    public function test_api_response_server_error_code()
    {

        $response = new ApiResponseServerError();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(500, $result->status());
    }

    public function test_api_response_server_error_content()
    {
        $response= new ApiResponseServerError();
        $request = new Request();
        $result = $response->toResponse($request);

        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'message'=>null,
                    'code'=>500
                ]
            ),
        );
    }
}
