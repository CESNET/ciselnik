<?php

namespace App\Livewire;

use App\Ldap\Organization;
use Livewire\Attributes\Url;
use Livewire\Component;

class SearchOrganizations extends Component
{
    #[Url(except: '')]
    public string $search = '';

    public function render()
    {
        if (! empty($this->search)) {
            $dc = Organization::whereContains('dc', $this->search)->get();
            $o = Organization::whereContains('o', $this->search)->get();
            $oabbrev = Organization::whereContains('oabbrev', $this->search)->get();
            $ico = Organization::where('ico', $this->search)->get();
            $l = Organization::whereContains('l', $this->search)->get();
            $street = Organization::whereContains('street', $this->search)->get();

            $organizations = $dc
                ->merge($o)
                ->merge($oabbrev)
                ->merge($ico)
                ->merge($l)
                ->merge($street);

            return view('livewire.search-organizations', [
                'organizations' => $organizations->unique(),
            ]);
        }

        return view('livewire.search-organizations', [
            'organizations' => Organization::orderBy('o')->limit(10)->get(),
        ]);
    }
}
