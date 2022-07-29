<?php

namespace App\Http\Livewire;

use App\Ldap\EjbcaOrganization;
use Livewire\Component;

class SearchCesnetCa extends Component
{
    public $search = '';

    public $queryString = ['search' => ['except' => '']];

    public function render()
    {
        if (! empty($this->search)) {
            return view('livewire.search-cesnet-ca', [
                'organizations' => EjbcaOrganization::orderBy('o')->whereContains('o', $this->search)->get(),
            ]);
        }

        return view('livewire.search-cesnet-ca', [
            'organizations' => EjbcaOrganization::orderBy('o')->get(),
        ]);
    }
}
