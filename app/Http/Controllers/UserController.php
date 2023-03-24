<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
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

    public function create()
    {
        $this->authorize('create', User::class);

        return view('users.create');
    }

    public function store(StoreUser $request)
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());
        $user->active = true;
        $user->save();

        return redirect()
            ->route('users.show', $user)
            ->with('status', __('users.stored', ['name' => $user->name]));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        $user->withTrashed();

        return view('users.show', [
            'user' => $user,
        ]);
    }
}
