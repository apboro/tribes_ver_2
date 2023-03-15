<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseForbidden;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseForbiddenTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_response_forbidden_code()
    {

        $response = new ApiResponseForbidden();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(403, $result->status());
    }

    public function test_api_response_forbidden_content()
    {
        $response= new ApiResponseForbidden();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'message'=>null,
                    'code'=>403
                ]
            ),
        );
    }
}
