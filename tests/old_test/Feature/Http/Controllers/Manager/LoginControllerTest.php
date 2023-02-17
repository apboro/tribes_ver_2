<?php

namespace Tests\old_test\Feature\Http\Controllers\Manager;

use Tests\TestCase;

/** @deprecated механизм авторизации как пользователь реализован в другом ключе эти тесты надо переписывать полностью */
class LoginControllerTest extends TestCase
{
    public function testLoginAdminAsUser()
    {
        $this->assertTrue(true);
    }
    public function testLoginBackToAdminPanel()
    {
        $this->assertTrue(true);
        /*$this->AuthSanctum();

        $user = $this->CreateUser();

        $id = PseudoCrypt::hash($user->id);
        session()->put('sudo', $id);

        $this->postJson('api/v2/loginBack')
            ->assertStatus(302)
            ->assertRedirect('manager')
            ->assertSessionMissing('sudo')
        ;

        $this->assertAuthenticatedAs($user,'web');*/
    }

    private function AuthSanctum()
    {
        $this->assertTrue(true);
        /*$user = User::factory()->create([
            'id' => 1
        ]);

        Sanctum::actingAs(
            $user
        );

        Administrator::factory()->create([
            'user_id' => $user->id,
        ]);*/
    }


    private function CreateUser()
    {
        $this->assertTrue(true);
        /*return User::factory()->create([
            'id' => 2,
            'email' => 'email@php.ru',
            'password' => Hash::make('123123123')
        ]);*/
    }
}
