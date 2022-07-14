<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchOrganizations;
use App\Ldap\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Livewire\Livewire;
use Tests\TestCase;

class SearchOrganizationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(SearchOrganizations::class);

        $component->assertStatus(200);
    }
}
