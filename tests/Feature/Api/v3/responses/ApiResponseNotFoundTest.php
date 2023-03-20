<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseNotFound;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseNotFoundTest extends TestCase
{

    public function test_api_response_not_found_code()
    {
        $response = new ApiResponseNotFound();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(404,$result->status());
    }

    public function test_api_response_not_found_content()
    {
        $response= new ApiResponseNotFound();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                'message'=>null,
                    'code'=>404
                ]
            ),
        );
    }

}
