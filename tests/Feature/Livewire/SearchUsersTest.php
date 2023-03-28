<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SearchUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render(): void
    {
        User::factory()->create();

        $component = Livewire::test(SearchUsers::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function the_component_is_present_in_users_index(): void
    {
        $admin = User::factory()->create(['admin' => true]);

        $this
            ->actingAs($admin)
            ->get(route('users.index'))
            ->assertSeeLivewire('search-users');
    }

    /** @test */
    public function the_component_allows_searching_users_by_name(): void
    {
        $admin = User::factory()->create(['admin' => true]);
        User::factory()->times(10)->create();
        $user = User::latest()->first();

        Livewire::test(SearchUsers::class)
            ->set('search', $user->name)
            ->assertSet('search', $user->name)
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertSee($user->uniqueid);
    }

    /** @test */
    public function the_component_allows_searching_users_by_email(): void
    {
        $admin = User::factory()->create(['admin' => true]);
        User::factory()->times(10)->create();
        $user = User::latest()->first();

        Livewire::test(SearchUsers::class, ['search' => $user->email])
            ->assertSet('search', $user->email)
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertSee($user->uniqueid);
    }

    /** @test */
    public function the_component_allows_searching_users_by_uniqueid(): void
    {
        $admin = User::factory()->create(['admin' => true]);
        User::factory()->times(10)->create();
        $user = User::latest()->first();

        Livewire::test(SearchUsers::class, ['search' => $user->uniqueid])
            ->assertSet('search', $user->uniqueid)
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertSee($user->uniqueid);
    }
}
