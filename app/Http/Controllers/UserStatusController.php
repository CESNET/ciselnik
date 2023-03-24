<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('everything');

        if ($request->user()->is($user)) {
            return redirect()
                ->route('users.show', $user)
                ->with('status', __('users.cannot_toggle_your_status'))
                ->with('color', 'red');
        }

        $user->active = $user->active ? false : true;
        $user->update();

        $status = $user->active ? 'active' : 'inactive';
        $color = $user->active ? 'green' : 'red';

        return redirect()
            ->route('users.show', $user)
            ->with('status', __("users.$status", ['name' => $user->name]))
            ->with('color', $color);
    }
}
