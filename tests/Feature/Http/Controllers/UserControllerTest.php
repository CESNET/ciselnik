<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_anonymouse_user_cannot_see_users_list(): void
    {
        User::factory()->times(5)->create();

        $this
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    #[Test]
    public function a_regualr_user_cannot_see_users_list(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();
        User::factory()->times(5)->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertForbidden();

        $this->assertEquals(route('users.index'), url()->current());
    }

    #[Test]
    public function an_admin_can_see_users_list(): void
    {
        $admin = User::factory()->create(['admin' => true]);
        User::factory()->times(5)->create();

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertSeeText(User::latest()->first()->name)
            ->assertOk();

        $this->assertEquals(route('users.index'), url()->current());
    }

    #[Test]
    public function a_user_isnt_shown_another_users_details(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('users.show', $anotherUser))
            ->assertForbidden();

        $this->assertEquals(route('users.show', $anotherUser), url()->current());
    }

    #[Test]
    public function a_user_is_shown_their_users_details(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('users.show', $user))
            ->assertSeeText($user->name)
            ->assertSeeText($user->uniqueid)
            ->assertSeeText($user->email)
            ->assertOk();

        $this->assertEquals(route('users.show', $user), url()->current());
    }

    #[Test]
    public function an_admin_is_shown_another_users_detail(): void
    {
        $admin = User::factory()->create(['admin' => true]);
        $anotherUser = User::factory()->create();

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('users.show', $anotherUser))
            ->assertSeeText($anotherUser->name)
            ->assertSeeText($anotherUser->uniqueid)
            ->assertSeeText($anotherUser->email)
            ->assertOk();

        $this->assertEquals(route('users.show', $anotherUser), url()->current());
    }
}
