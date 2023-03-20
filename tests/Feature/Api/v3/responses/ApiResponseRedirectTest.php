<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseRedirect;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseRedirectTest extends TestCase
{
    public function test_api_response_redirect_code()
    {
        $response = new ApiResponseRedirect();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(301,$result->status());
    }

    public function test_api_response_redirect_content()
    {
        $response= new ApiResponseRedirect();
        $request = new Request();
        $result = $response->toResponse($request);

        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'code'=>301,
                    'message'=>null,
                ]
            ),
        );
    }
}
