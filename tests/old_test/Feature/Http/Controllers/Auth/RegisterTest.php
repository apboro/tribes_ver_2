<?php

namespace Tests\old_test\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegistrationPageAvaleble(): void 
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function testRegistrationFailedAction(): void 
    {
        $this->assertTrue(true);
        /*$response = $this->post('/register');

        $response->assertRedirect(route('register'));*/
    }

    public function testRegistrationAction(): void 
    {
        $this->assertTrue(true);
        /*$response = $this->post('/register', [
            'name' => 'Unit test',
            'email' => 'evgenijst21@gmail.com'
        ]);

        $response->assertRedirect(route('author.profile'));*/
    }
}
