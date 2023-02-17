<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseSuccess;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseSuccessTest extends TestCase
{
    public function test_api_response_success_code()
    {
        $response = new ApiResponseSuccess();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(200,$result->status());
    }

    public function test_api_response_success_content()
    {
        $response= new ApiResponseSuccess();
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
