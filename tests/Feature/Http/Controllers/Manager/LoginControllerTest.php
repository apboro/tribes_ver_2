<?php

namespace Tests\Feature\Http\Controllers\Manager;

use App\Helper\PseudoCrypt;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class LoginControllerTest extends TestCase
{
    public function testLoginAdminAsUser()
    {
        $this->AuthSanctum();

        $user = $this->CreateUser();

        $id = PseudoCrypt::hash(Auth::id());;

        $this->postJson('api/v2/loginAs', ['id' => $user->id])
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('sudo', $id);

        $this->assertAuthenticatedAs($user,'web');
    }
    public function testLoginBackToAdminPanel()
    {
        $this->AuthSanctum();

        $user = $this->CreateUser();

        $id = PseudoCrypt::hash($user->id);
        session()->put('sudo', $id);

        $this->postJson('api/v2/loginBack')
            ->assertStatus(302)
            ->assertRedirect('manager')
            ->assertSessionMissing('sudo')
        ;

        $this->assertAuthenticatedAs($user,'web');
    }

    private function AuthSanctum()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'id' => 1
            ])
        );
    }


    private function CreateUser()
    {
        return User::factory()->create([
            'id' => 2,
            'email' => 'email@php.ru',
            'password' => Hash::make('123123123')
        ]);
    }
}
