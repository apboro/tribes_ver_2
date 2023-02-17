<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseError;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseErrorTest extends TestCase
{
    public function test_api_response_error_code()
    {
        $response = new ApiResponseError();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(400, $result->status());
    }

    public function test_api_response_error_content()
    {
        $response= new ApiResponseError();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'message'=>null,
                    'payload' =>[]
                ]
            ),
        );
    }
}
