<?php

namespace Tests\Feature\Http\Controllers;

use App\Ldap\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function an_organization_can_be_created()
    {
        DirectoryEmulator::setup('default');

        $faker = \Faker\Factory::create('cs_CZ');

        $dc = $this->faker->word();

        $organization = Organization::create([
            'dc' => strtoupper($dc),
            'ico' => $ico = $faker->ico(),
        ]);

        $this->assertEquals(1, Organization::all()->count());
        $this->assertEquals('dc=' . strtoupper($dc), $organization->getRdn());
        $this->assertEquals($ico, $organization->getFirstAttribute('ico'));

        DirectoryEmulator::tearDown();
    }
}
