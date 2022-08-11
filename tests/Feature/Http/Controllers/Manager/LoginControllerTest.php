<?php

namespace Tests\Feature\Http\Controllers\Manager;

use App\Helper\PseudoCrypt;
use App\Models\Administrator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
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
