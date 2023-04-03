<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ShibbolethControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function if_no_shibboleth_just_show_a_meesage(): void
    {
        $this
            ->get(route('login'))
            ->assertOk()
            ->assertSeeText('login');
    }

    /** @test */
    public function shibboleth_login_redirects_correctly(): void
    {
        $this
            ->withServerVariables(['Shib-Handler' => 'http://localhost'])
            ->get('login');

        $this->assertEquals('http://localhost/login', url()->current());
    }

    /** @test */
    public function a_newly_created_user_cannot_login(): void
    {
        $this
            ->followingRedirects()
            ->withServerVariables([
                'uniqueId' => $id = fake()->unique()->safeEmail(),
                'cn' => fake()->name(),
                'mail' => $id,
            ])
            ->get('auth')
            ->assertSeeInOrder([
                __('welcome.account_created_header'),
                __('welcome.account_created_info'),
            ]);

        $this->assertEquals('http://localhost/auth', url()->current());
    }

    /** @test */
    public function an_existing_user_with_inactive_account_cannot_login(): void
    {
        $user = User::factory()->create(['active' => false]);
        $user->refresh();

        $this
            ->followingRedirects()
            ->withServerVariables([
                'uniqueId' => $user->uniqueid,
                'cn' => $user->name,
                'mail' => $user->mail,
            ])
            ->get('auth')
            ->assertSeeInOrder([
                __('welcome.account_inactive'),
                __('welcome.account_inactive_info'),
            ]);

        $this->assertFalse(Auth::check());
        $this->assertTrue(Auth::guest());

        $this->assertEquals('http://localhost/auth', url()->current());
    }

    /** @test */
    public function an_existing_user_with_active_account_can_login(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        $this
            ->followingRedirects()
            ->withServerVariables([
                'uniqueId' => $user->uniqueid,
                'cn' => $user->name,
                'mail' => $user->mail,
            ])
            ->get('auth');

        $this->assertEquals(route('home'), url()->current());
        $this->assertTrue(Auth::check());
    }

    /** @test */
    public function a_user_can_log_out(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();

        Auth::login($user);
        Session::regenerate();

        $this->assertTrue(Auth::check());
        $this->assertFalse(Auth::guest());

        $this
            ->actingAs($user)
            ->get(route('logout'))
            ->assertRedirect('http://localhost/Shibboleth.sso/Logout');
    }
}
