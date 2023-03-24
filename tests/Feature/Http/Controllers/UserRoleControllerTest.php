<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRoleControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_anonymouse_user_cannot_toggle_users_role()
    {
        $user = User::factory()->create();

        $this
            ->followingRedirects()
            ->patch(route('users.role', $user))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function a_user_cannot_toggle_another_users_role()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.role', $anotherUser))
            ->assertForbidden();

        $this->assertEquals(route('users.role', $anotherUser), url()->current());
    }

    /** @test */
    public function a_user_cannot_toggle_their_role()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.role', $user))
            ->assertForbidden();

        $this->assertEquals(route('users.role', $user), url()->current());
    }

    /** @test */
    public function an_admin_can_toggle_another_users_role()
    {
        $admin = User::factory()->create(['admin' => true]);
        $anotherUser = User::factory()->create();

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.role', $anotherUser))
            ->assertSeeText(__('users.admin_granted', ['name' => $anotherUser->name]))
            ->assertSeeText($anotherUser->name)
            ->assertSeeText($anotherUser->uniqueid)
            ->assertSeeText($anotherUser->email)
            ->assertOk();

        $this->assertEquals(route('users.show', $anotherUser), url()->current());

        $anotherUser->refresh();
        $this->assertTrue($anotherUser->admin);

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.role', $anotherUser))
            ->assertSeeText(__('users.admin_revoked', ['name' => $anotherUser->name]))
            ->assertSeeText($anotherUser->name)
            ->assertSeeText($anotherUser->uniqueid)
            ->assertSeeText($anotherUser->email)
            ->assertOk();

        $this->assertEquals(route('users.show', $anotherUser), url()->current());

        $anotherUser->refresh();
        $this->assertFalse($anotherUser->admin);
    }

    /** @test */
    public function an_admin_cannot_toggle_their_role()
    {
        $admin = User::factory()->create(['admin' => true]);

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.role', $admin))
            ->assertSeeText(__('users.cannot_toggle_your_role'))
            ->assertOk();

        $this->assertEquals(route('users.show', $admin), url()->current());
    }
}
