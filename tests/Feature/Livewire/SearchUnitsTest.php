<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchUnits;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SearchUnitsTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(SearchUnits::class);

        $component->assertStatus(200);
    }
}
