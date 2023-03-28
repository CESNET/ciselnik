<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user): RedirectResponse
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
