<?php

namespace App\Livewire;

use App\Ldap\Unit;
use Livewire\Attributes\Url;
use Livewire\Component;

class SearchUnits extends Component
{
    #[Url(except: '')]
    public string $search = '';

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
