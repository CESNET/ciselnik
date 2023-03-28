<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use Tests\TestCase;

class CesnetCaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_anonymouse_user_isnt_shown_a_cesnet_ca_list(): void
    {
        $this
            ->followingRedirects()
            ->get(route('cesnet-ca.index'))
            ->assertOk()
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function an_anonymouse_user_cannot_see_the_form_to_add_a_new_cesnet_ca(): void
    {
        $this
            ->followingRedirects()
            ->get(route('cesnet-ca.create'))
            ->assertOk()
            ->assertSeeText('login');

        $this->assertEquals(route('login'), url()->current());
    }

    /** @test */
    public function a_user_with_active_account_can_see_a_cesnet_ca_list(): void
    {
        DirectoryEmulator::setup('ejbca');

        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->get(route('cesnet-ca.index'))
            ->assertOk();

        $this->assertEquals(route('cesnet-ca.index'), url()->current());
    }

    /** @test */
    public function a_user_with_active_account_can_see_the_form_to_add_a_new_cesnet_ca(): void
    {
        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->get(route('cesnet-ca.create'))
            ->assertOk()
            ->assertSeeInOrder([
                __('ldap.o'),
                __('organizations.placeholder_o'),
                __('common.back'),
                __('common.add'),
            ]);
    }

    /** @test */
    public function a_user_with_active_account_can_store_a_new_cesnet_ca(): void
    {
        DirectoryEmulator::setup('ejbca');

        $user = User::factory()->create(['active' => true]);

        $this
            ->followingRedirects()
            ->actingAs($user)
            ->post(route('cesnet-ca.store', [
                'o' => fake()->word(3, true),
            ]))
            ->assertOk()
            ->assertSeeText(__('organizations.stored'));
    }
}
