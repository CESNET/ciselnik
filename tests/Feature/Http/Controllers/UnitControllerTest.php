<?php

namespace Tests\Feature\Http\Controllers;

use App\Ldap\Organization;
use App\Ldap\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UnitControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_anonymouse_user_cannot_see_units_index(): void
    {
        $this
            ->followingRedirects()
            ->get(route('units.index'))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_user_cannot_see_the_form_to_add_a_new_unit(): void
    {
        $this
            ->followingRedirects()
            ->get(route('units.create'))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_active_user_can_see_units_index(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->get(route('units.index'))
            ->assertOk();

        $this->assertEquals(route('units.index'), url()->current());

        DirectoryEmulator::tearDown();
    }

    #[Test]
    public function an_active_user_can_see_the_form_to_add_a_new_unit(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->get(route('units.create'))
            ->assertOk();

        $this->assertEquals(route('units.create'), url()->current());

        DirectoryEmulator::tearDown();
    }

    #[Test]
    public function an_active_user_can_store_a_new_unit(): void
    {
        DirectoryEmulator::setup('default');
        Mail::fake();

        $user = User::factory()->create(['active' => true]);
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

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->post(route('units.store', [
                'dc' => $dc = fake()->word(),
                'o;lang-cs' => fake()->company(),
                'oabbrev;lang-cs' => fake()->word(),
                'ou;lang-cs' => fake()->company(),
                'ouabbrev;lang-cs' => fake()->word(),
                'oparentpointer' => $organization->getRdn(),
                'street' => fake()->streetAddress(),
                'l' => fake()->city(),
                'postalcode' => fake()->randomNumber(),
                'c' => fake()->stateAbbr(),
                'labeleduri' => fake()->url(),
            ]))
            ->assertOk()
            ->assertSeeText(__('units.stored'));

        $unit = Unit::whereDc($dc)->firstOrFail();
        $this->assertEquals(route('units.show', $unit), url()->current());

        DirectoryEmulator::tearDown();
        ob_end_clean();
    }

    #[Test]
    public function an_active_user_can_see_the_update_form_for_a_unit(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['active' => true]);
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

        $this
            ->actingAs($user)
            ->get(route('units.edit', $unit))
            ->assertOk();

        $this->assertEquals(route('units.edit', $unit), url()->current());

        DirectoryEmulator::tearDown();
        ob_end_clean();
    }

    #[Test]
    public function an_active_user_can_update_an_existing_unit(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['active' => true]);
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
        $new_unit = [
            'o;lang-cs' => fake()->company(),
            'o' => fake()->company(),
            'oabbrev;lang-cs' => fake()->word(),
            'oabbrev' => fake()->word(),
            'ou;lang-cs' => fake()->company(),
            'ou' => fake()->company(),
            'ouabbrev;lang-cs' => fake()->word(),
            'ouabbrev' => fake()->word(),
            'oparentpointer' => $organization->getRdn(),
            'street' => fake()->streetAddress(),
            'l' => fake()->city(),
            'postalcode' => fake()->randomNumber(),
            'c' => fake()->stateAbbr(),
            'labeleduri' => fake()->url(),
        ];

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('units.update', $unit), $new_unit)
            ->assertOk()
            ->assertSeeText(__('units.updated'));

        DirectoryEmulator::tearDown();
        ob_end_clean();
    }

    #[Test]
    public function an_admin_can_delete_a_unit(): void
    {
        DirectoryEmulator::setup('default');

        $admin = User::factory()->create(['active' => true, 'admin' => true]);
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

        $dn = $unit->getDn();

        $this
            ->followingRedirects()
            ->actingAs($admin)
            ->delete(route('units.destroy', $unit))
            ->assertOk()
            ->assertSeeText(__('units.deleted', ['dn' => $dn]));

        DirectoryEmulator::tearDown();
    }
}
