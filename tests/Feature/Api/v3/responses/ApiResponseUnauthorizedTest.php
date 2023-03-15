<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseUnauthorized;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseUnauthorizedTest extends TestCase
{
    public function test_api_response_unauthorized_code()
    {
        $response = new ApiResponseUnauthorized();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(401,$result->status());
    }

    public function test_api_response_unauthorized_content()
    {
        $response= new ApiResponseUnauthorized();
        $request = new Request();
        $result = $response->toResponse($request);

        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'message'=>null,
                    'code' =>401
                ]
            ),
        );
    }
}
