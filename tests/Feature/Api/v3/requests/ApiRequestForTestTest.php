<?php

namespace Tests\Feature\Api\v3\requests;

use App\Exceptions\ApiUnauthorizedException;
use App\Http\ApiRequests\ApiRequestForTest;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ApiRequestForTestTest extends TestCase
{

    public function test_api_request_failed_authorization()
    {
        $this->expectException(ApiUnauthorizedException::class);
        $request = new ApiRequestForTest();
        $request->authorize();
    }

    public function test_api_request_failed_validation(){
        $this->expectException(ValidationException::class);
        $request = new ApiRequestForTest(['test'=>123]);
        $request->validate([
            'test'=>'required|min:5'
        ]);
    }

    public function test_api_request_success_validation(){
        $data = ['test'=>123];
        $request = new ApiRequestForTest($data);
        $this->assertEquals($request->validate(['test'=>'required']),$data);
    }

    public function test_api_request_transform(){
        $request = new ApiRequestForTest(['test'=>'TEST']);
        $request->prepareForValidation();
        $this->assertEquals('test',$request->input('test'));

    }
}
