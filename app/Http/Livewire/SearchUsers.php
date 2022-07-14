<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SearchUsers extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.search-users', [
            'users' => User::query()
                ->withTrashed()
                ->search($this->search)
                ->orderBy('name')
                ->paginate(10),
            'search' => $this->search,
        ]);
    }
}
