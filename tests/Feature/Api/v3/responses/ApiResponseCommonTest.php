<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseCommon;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseCommonTest extends TestCase
{
    public function test_api_response_common_code()
    {
        $response = new ApiResponseCommon();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(200, $result->status());
    }

    public function test_api_response_common_content()
    {
        $response= new ApiResponseCommon();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'data'=>[],
                    'message'=>null,
                    'payload' =>[]
                ]
            ),
        );
    }
}
