<?php

namespace App\Http\Livewire;

use App\Ldap\Unit;
use Livewire\Component;

class SearchUnits extends Component
{
    public $search = '';

    public $queryString = ['search'];

    public function render()
    {
        if (! empty($this->search)) {
            $dc = Unit::whereHas('oparentpointer')->whereContains('dc', $this->search)->get();
            $o = Unit::whereHas('oparentpointer')->whereContains('o', $this->search)->get();
            $oabbrev = Unit::whereHas('oparentpointer')->whereContains('oabbrev', $this->search)->get();
            $ou = Unit::whereHas('oparentpointer')->whereContains('ou', $this->search)->get();
            $l = Unit::whereHas('oparentpointer')->whereContains('l', $this->search)->get();
            $street = Unit::whereHas('oparentpointer')->whereContains('street', $this->search)->get();

            $units = $dc
                ->merge($o)
                ->merge($oabbrev)
                ->merge($ou)
                ->merge($l)
                ->merge($street);

            return view('livewire.search-units', [
                'units' => $units->unique(),
            ]);
        }

        return view('livewire.search-units', [
            'units' => Unit::whereHas('oparentpointer')->orderBy('o')->limit(10)->get(),
        ]);
    }
}
