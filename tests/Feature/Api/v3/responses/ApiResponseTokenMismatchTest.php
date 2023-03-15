<?php

namespace Tests\Feature\Api\v3\responses;


use App\Http\ApiResponses\ApiResponseTokenMismatch;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseTokenMismatchTest extends TestCase
{
    public function test_api_response_token_mismatch_code()
    {
        $response = new ApiResponseTokenMismatch();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(419,$result->status());
    }

    public function test_api_response_token_mismatch_content()
    {
        $response= new ApiResponseTokenMismatch();
        $request = new Request();
        $result = $response->toResponse($request);

        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'message'=>null,
                    'code' =>419
                ]
            ),
        );
    }
}
