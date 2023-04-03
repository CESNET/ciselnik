<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->withTrashed()
            ->search(request('search'))
            ->orderBy('name')
            ->paginate();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());
        $user->active = true;
        $user->save();

        return redirect()
            ->route('users.show', $user)
            ->with('status', __('users.stored', ['name' => $user->name]));
    }

    public function show(User $user): View
    {
        $this->authorize('view', $user);
        $user->withTrashed();

        return view('users.show', [
            'user' => $user,
        ]);
    }
}
