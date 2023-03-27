<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchOrganizations;
use App\Ldap\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Livewire\Livewire;
use Tests\TestCase;

class SearchOrganizationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        DirectoryEmulator::setup('default');

        $component = Livewire::test(SearchOrganizations::class);

        $component->assertStatus(200);

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function the_component_is_present_in_organizations_index()
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->get(route('organizations.index'))
            ->assertSeeLivewire('search-organizations');

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function the_component_allows_searching_organizations()
    {
        DirectoryEmulator::setup('default');

        $organization = Organization::create([
            'o' => fake()->company(),
        ]);

        Livewire::test(SearchOrganizations::class)
            ->set('search', $organization->getFirstAttribute('o'))
            ->assertSet('search', $organization->getFirstAttribute('o'))
            ->assertSee($organization->getFirstAttribute('o'))
            ->assertSee($organization->getDn());

        DirectoryEmulator::tearDown();
    }
}
