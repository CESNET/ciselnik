<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_anonymouse_user_isnt_shown_a_users_list()
    {
        $this
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_isnt_shown_a_users_detail()
    {
        $user = User::factory()->create();

        $this
            ->followingRedirects()
            ->get(route('users.show', $user))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_isnt_shown_a_form_to_add_a_new_user()
    {
        $this
            ->followingRedirects()
            ->get(route('users.create'))
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function a_user_isnt_shown_a_users_list()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertForbidden();

        $this->assertEquals(route('users.index'), url()->current());
    }

    /** @test */
    public function a_user_isnt_shown_another_users_detail()
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

    /** @test */
    public function a_user_isnt_shown_a_form_to_add_a_new_user()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('users.create'))
            ->assertForbidden();

        $this->assertEquals(route('users.create'), url()->current());
    }

    /** @test */
    public function a_user_is_shown_their_users_detail()
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

    /** @test */
    public function an_admin_is_shown_a_users_list()
    {
        $admin = User::factory()->create(['admin' => true]);

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('users.index'))
            ->assertSeeText($admin->name)
            ->assertSeeText($admin->uniqueid)
            ->assertSeeText($admin->email)
            ->assertOk();

        $this->assertEquals(route('users.index'), url()->current());
    }

    /** @test */
    public function an_admin_is_shown_another_users_detail()
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

    /** @test */
    public function an_admin_is_shown_a_form_to_add_a_new_user()
    {
        $admin = User::factory()->create(['admin' => true]);

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('users.create'))
            ->assertSeeText(__('users.add'))
            ->assertOk();

        $this->assertEquals(route('users.create'), url()->current());
    }

    /** @test */
    public function an_admin_can_add_a_new_user()
    {
        $admin = User::factory()->create(['admin' => true]);

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->post(route('users.store', [
                'name' => $name = "{$this->faker->firstName()} {$this->faker->lastName()}",
                'uniqueid' => $uniqueid = $this->faker->safeEmail(),
                'email' => $email = $this->faker->safeEmail(),
            ]))
            ->assertSeeText(__('users.stored', ['name' => $name]))
            ->assertSeeText($name)
            ->assertSeeText($uniqueid)
            ->assertSeeText($email)
            ->assertOk();

        $this->assertEquals(route('users.show', User::all()->last()->id), url()->current());
    }

    /** @test */
    public function an_admin_can_delete_another_user()
    {
        $admin = User::factory()->create(['admin' => true]);
        $anotherUser = User::factory()->create();

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->delete(route('users.destroy', $anotherUser))
            ->assertSeeText(__('users.deleted', ['name' => $anotherUser->name]))
            ->assertSeeText($anotherUser->name)
            ->assertSeeText($anotherUser->uniqueid)
            ->assertSeeText($anotherUser->email)
            ->assertOk();

        $this->assertEquals(route('users.show', $anotherUser), url()->current());

        $anotherUser->refresh();
        $this->assertTrue($anotherUser->trashed());
    }

    /** @test */
    public function an_admin_can_restore_deleted_user()
    {
        $admin = User::factory()->create(['admin' => true]);
        $anotherUser = User::factory()->create(['deleted_at' => now()]);

        $this->assertTrue($anotherUser->trashed());

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.restore', $anotherUser))
            ->assertSeeText(__('users.restored', ['name' => $anotherUser->name]))
            ->assertSeeText($anotherUser->name)
            ->assertSeeText($anotherUser->uniqueid)
            ->assertSeeText($anotherUser->email)
            ->assertOk();

        $this->assertEquals(route('users.show', $anotherUser), url()->current());

        $anotherUser->refresh();
        $this->assertFalse($anotherUser->trashed());
    }

    /** @test */
    public function an_admin_cannot_delete_themself()
    {
        $admin = User::factory()->create(['admin' => true]);

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->delete(route('users.destroy', $admin))
            ->assertSeeText(__('users.cannot_delete_yourself'))
            ->assertOk();

        $this->assertEquals(route('users.show', $admin), url()->current());
    }

    /** @test */
    public function an_admin_cannot_restore_themself()
    {
        $admin = User::factory()->create(['admin' => true, 'deleted_at' => now()]);

        $this->assertTrue($admin->trashed());

        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('users.restore', $admin))
            ->assertSeeText(__('users.cannot_restore_yourself'))
            ->assertOk();

        $this->assertEquals(route('users.show', $admin), url()->current());
    }
}
