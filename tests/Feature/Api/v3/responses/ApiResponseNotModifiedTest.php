<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseNotModified;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiResponseNotModifiedTest extends TestCase
{

    public function test_api_response_not_modified_code()
    {
        $response = new ApiResponseNotModified();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(304,$result->status());
    }

    public function test_api_response_not_modified_content()
    {
        $response= new ApiResponseNotModified();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertJson($result->content());
    }

}
