<?php

namespace Tests\Feature\Http\Controllers;

use App\Ldap\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_anonymouse_user_isnt_show_the_list_of_organizations()
    {
        $this
            ->followingRedirects()
            ->get(route('organizations.index'))
            ->assertOk()
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function a_user_with_active_account_can_see_the_list_of_organizations()
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
    public function a_user_with_active_account_can_see_the_form_to_add_a_new_organization()
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
    public function an_active_user_can_add_a_new_organization()
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
    public function an_organization_can_be_edited()
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
    public function an_organization_can_be_created()
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
}
