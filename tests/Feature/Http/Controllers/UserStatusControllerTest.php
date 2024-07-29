<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserStatusControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function an_anonymouse_user_cannot_toggle_users_status(): void
    {
        $user = User::factory()->create();

        $this
            ->followingRedirects()
            ->patch(route('users.status', $user))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    #[Test]
    public function a_user_cannot_toggle_another_users_status(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.status', $anotherUser))
            ->assertForbidden();

        $this->assertEquals(route('users.status', $anotherUser), url()->current());
    }

    #[Test]
    public function a_user_cannot_toggle_their_status(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('users.status', $user))
            ->assertForbidden();

        $this->assertEquals(route('users.status', $user), url()->current());
    }

    #[Test]
    public function an_admin_can_toggle_another_users_status(): void
    {
        $admin = User::factory()->create(['admin' => true]);
        $anotherUser = User::factory()->create();

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.status', $anotherUser))
            ->assertSeeText(__('users.inactive', ['name' => $anotherUser->name]))
            ->assertOk();

        $this->assertEquals(route('users.show', $anotherUser), url()->current());

        $anotherUser->refresh();
        $this->assertFalse($anotherUser->active);

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.status', $anotherUser))
            ->assertSeeText(__('users.active', ['name' => $anotherUser->name]))
            ->assertOk();

        $this->assertEquals(route('users.show', $anotherUser), url()->current());

        $anotherUser->refresh();
        $this->assertTrue($anotherUser->active);
    }

    #[Test]
    public function an_admin_cannot_toggle_their_status(): void
    {
        $admin = User::factory()->create(['admin' => true]);

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.status', $admin))
            ->assertSeeText(__('users.cannot_toggle_your_status'))
            ->assertOk();

        $this->assertEquals(route('users.show', $admin), url()->current());
    }
}
