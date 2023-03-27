<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchCesnetCa;
use App\Ldap\EjbcaOrganization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Livewire\Livewire;
use Tests\TestCase;

class SearchCesnetCaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        DirectoryEmulator::setup('ejbca');

        $component = Livewire::test(SearchCesnetCa::class);

        $component->assertStatus(200);

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function the_component_is_present_in_cesnet_ca_index()
    {
        DirectoryEmulator::setup('ejbca');

        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->get(route('cesnet-ca.index'))
            ->assertSeeLivewire('search-cesnet-ca');

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function the_component_allows_searching_cesnet_ca()
    {
        DirectoryEmulator::setup('ejbca');

        User::factory()->create(['active' => true]);
        $organization = EjbcaOrganization::create([
            'o' => fake()->company(),
        ]);

        Livewire::test(SearchCesnetCa::class)
            ->set('search', $organization->getFirstAttribute('o'))
            ->assertSet('search', $organization->getFirstAttribute('o'))
            ->assertSee($organization->getFirstAttribute('o'))
            ->assertSee($organization->getDn());

        DirectoryEmulator::tearDown();
    }
}
