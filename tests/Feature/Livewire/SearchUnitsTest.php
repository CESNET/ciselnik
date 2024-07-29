<?php

namespace Tests\Feature\Livewire;

use App\Ldap\Organization;
use App\Ldap\Unit;
use App\Livewire\SearchUnits;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchUnitsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_component_can_render(): void
    {
        DirectoryEmulator::setup('default');

        $component = Livewire::test(SearchUnits::class);

        $component->assertStatus(200);

        DirectoryEmulator::tearDown();
    }

    #[Test]
    public function the_component_is_present_in_units_index(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('units.index'))
            ->assertSeeLivewire('search-units');

        DirectoryEmulator::tearDown();
    }

    #[Test]
    public function the_component_allows_searching_units(): void
    {
        DirectoryEmulator::setup('default');

        $organization = Organization::create([
            'dc' => fake()->word(),
            'o;lang-cs' => fake()->company(),
            'oabbrev;lang-cs' => fake()->word(),
            'ico' => fake()->randomNumber(),
            'street' => fake()->streetAddress(),
            'l' => fake()->city(),
            'postalcode' => fake()->randomNumber(),
            'c' => fake()->stateAbbr(),
            'labeleduri' => fake()->url(),
        ]);
        $unit = Unit::create([
            'dc' => fake()->word(),
            'o;lang-cs' => fake()->company(),
            'o' => fake()->company(),
            'oabbrev;lang-cs' => fake()->word(),
            'ou;lang-cs' => fake()->company(),
            'ouabbrev;lang-cs' => fake()->word(),
            'oparentpointer' => $organization->getRdn(),
            'street' => fake()->streetAddress(),
            'l' => fake()->city(),
            'postalcode' => fake()->randomNumber(),
            'c' => fake()->stateAbbr(),
            'labeleduri' => fake()->url(),
        ]);

        Livewire::test(SearchUnits::class)
            ->set('search', $unit->getFirstAttribute('o'))
            ->assertSet('search', $unit->getFirstAttribute('o'))
            ->assertSee($unit->getFirstAttribute('o;lang-cs'))
            ->assertSee($unit->getDn());

        DirectoryEmulator::tearDown();
    }
}
