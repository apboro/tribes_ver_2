<?php

namespace Tests\Feature\Api\v3\responses;

use App\Http\ApiResponses\ApiResponseValidationError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ApiResponseValidationErrorTest extends TestCase
{
    public function test_api_response_validation_error_code()
    {
        $response = new ApiResponseValidationError();
        $request = new Request();
        $result = $response->toResponse($request);
        $this->assertEquals(422,$result->status());
    }

    public function test_api_response_validation_error_content()
    {
        $response= new ApiResponseValidationError();
        $request = new Request();
        $result = $response->toResponse($request);

        $this->assertJson($result->content());
        $this->assertJsonStringEqualsJsonString(
            $result->content(),
            json_encode([
                    'message'=>null,
                    'code' =>422
                ]
            ),
        );
    }


    public function test_api_response_validation_error_language()
    {
        App::setLocale('ru');
        $response = new ApiResponseValidationError();
        $request = new Request();
        $response->message('common.validation_error');
        $result = $response->toResponse($request);
        $decoded = json_decode($result->content());
        $this->assertEquals(422,$result->status());
        $this->assertEquals('Не все поля корректно заполнены',$decoded->message);
    }
}
