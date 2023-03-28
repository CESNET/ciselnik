<?php

namespace Tests\Feature\Http\Controllers;

use App\Ldap\Organization;
use App\Ldap\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_anonymouse_user_isnt_show_the_list_of_organizations(): void
    {
        $this
            ->followingRedirects()
            ->get(route('organizations.index'))
            ->assertOk()
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function a_user_with_active_account_can_see_the_list_of_organizations(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->get(route('organizations.index'))
            ->assertOk();

        $this->assertEquals(route('organizations.index'), url()->current());
    }

    /** @test */
    public function a_user_with_active_account_can_see_the_form_to_add_a_new_organization(): void
    {
        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->get(route('organizations.create'))
            ->assertOk()
            ->assertSeeInOrder([
                __('organizations.add_organization'),
                __('ldap.dc'),
                __('ldap.o'),
                __('ldap.oAbbrev'),
                __('ldap.iCO'),
                __('ldap.street'),
                __('ldap.l'),
                __('ldap.postalCode'),
                __('ldap.c'),
                __('ldap.labeledURI'),
                __('common.add'),
            ]);

        $this->assertEquals(route('organizations.create'), url()->current());
    }

    /** @test */
    public function an_active_user_can_add_a_new_organization(): void
    {
        DirectoryEmulator::setup('default');
        Mail::fake();

        $user = User::factory()->create(['active' => true]);

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->post(route('organizations.store', [
                'dc' => fake()->word(),
                'o;lang-cs' => fake()->company(),
                'oabbrev;lang-cs' => fake()->word(),
                'ico' => fake()->randomNumber(),
                'street' => fake()->streetAddress(),
                'l' => fake()->city(),
                'postalcode' => fake()->randomNumber(),
                'c' => fake()->stateAbbr(),
                'labeleduri' => fake()->url(),
            ]))
            ->assertOk()
            ->assertSeeText(__('organizations.stored'));

        $this->assertCount(1, Organization::all());
        $organization = Organization::first();
        $this->assertEquals(route('organizations.show', $organization), url()->current());

        DirectoryEmulator::tearDown();
        ob_end_clean();
    }

    /** @test */
    public function an_active_user_is_redirected_to_a_unit_when_accessing_unit_via_organizations(): void
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
            'oparentpointer' => $organization->getDn(),
            'street' => fake()->streetAddress(),
            'l' => fake()->city(),
            'postalcode' => fake()->randomNumber(),
            'c' => fake()->stateAbbr(),
            'labeleduri' => fake()->url(),
        ]);

        $this->assertCount(1, User::all());
        $this->assertCount(2, Organization::all());
        $this->assertCount(2, Unit::all());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->get(route('organizations.show', $unit))
            ->assertOk()
            ->assertSeeText(__('units.redirected_from_organizations'));

        $this->assertEquals(route('units.show', $unit), url()->current());

        DirectoryEmulator::tearDown();
        ob_end_clean();
    }

    /** @test */
    public function an_organization_can_be_edited(): void
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

        $this
            ->actingAs($user)
            ->get(route('organizations.edit', $organization))
            ->assertOk();

        $this->assertEquals(route('organizations.edit', $organization), url()->current());

        DirectoryEmulator::tearDown();
        ob_end_clean();
    }

    /** @test */
    public function an_organization_can_be_created(): void
    {
        DirectoryEmulator::setup('default');

        $organization = Organization::create([
            'dc' => strtoupper($dc = fake()->word()),
            'ico' => $ico = fake('cs_CZ')->ico(),
        ]);

        $this->assertEquals(1, Organization::all()->count());
        $this->assertEquals('dc='.strtoupper($dc), $organization->getRdn());
        $this->assertEquals($ico, $organization->getFirstAttribute('ico'));

        DirectoryEmulator::tearDown();
    }

    /** @test */
    public function an_active_user_can_update_an_organization(): void
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
        $new_organization = [
            'o;lang-cs' => $o = fake()->company(),
            'o' => $o,
            'oabbrev;lang-cs' => $oabbrev = fake()->word(),
            'oabbrev' => $oabbrev,
            'street' => fake()->streetAddress(),
            'l' => fake()->city(),
            'postalcode' => fake()->randomNumber(),
            'c' => fake()->stateAbbr(),
            'labeleduri' => fake()->url(),
        ];

        $this->assertCount(1, User::all());
        $this->assertCount(1, Organization::all());

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->patch(route('organizations.update', $organization), $new_organization)
            ->assertOk()
            ->assertSeeText(__('organizations.updated'));

        $this->assertEquals(route('organizations.show', $organization), url()->current());

        DirectoryEmulator::tearDown();
        ob_end_clean();
    }

    /** @test */
    public function an_admin_can_delete_an_organization(): void
    {
        DirectoryEmulator::setup('default');

        $user = User::factory()->create(['active' => true, 'admin' => true]);
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

        $dn = $organization->getDn();

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->delete(route('organizations.destroy', $organization))
            ->assertOk()
            ->assertSeeText(__('organizations.deleted', ['dn' => $dn]));

        $this->assertEquals(route('organizations.index'), url()->current());

        DirectoryEmulator::tearDown();
    }
}
