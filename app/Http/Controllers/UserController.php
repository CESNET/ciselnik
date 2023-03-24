<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        if ($request->user()->is($user)) {
            return redirect()
                ->route('users.show', $user)
                ->with('status', __('users.cannot_delete_yourself'))
                ->with('color', 'red');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('users.show', $user)
            ->with('status', __('users.deleted', ['name' => $name]));
    }

    public function restore(Request $request, User $user)
    {
        $this->authorize('restore', $user);

        if ($request->user()->is($user)) {
            return redirect()
                ->route('users.show', $user)
                ->with('status', __('users.cannot_restore_yourself'))
                ->with('color', 'red');
        }

        $user->restore();

        return redirect()
            ->route('users.show', $user)
            ->with('status', __('users.restored', ['name' => $user->name]));
    }

    public function role(Request $request, User $user)
    {
        $this->authorize('everything');

        if ($request->user()->is($user)) {
            return redirect()
                ->route('users.show', $user)
                ->with('status', __('users.cannot_toggle_your_role'))
                ->with('color', 'red');
        }

        $user->admin = $user->admin ? false : true;
        $user->update();

        $admin = $user->admin ? 'admin_granted' : 'admin_revoked';
        $color = $user->admin ? 'green' : 'red';

        return redirect()
            ->route('users.show', $user)
            ->with('status', __("users.$admin", ['name' => $user->name]))
            ->with('color', $color);
    }
}
