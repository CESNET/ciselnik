<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FakeController extends Controller
{
    public function store(Request $request)
    {
        if (app()->environment(['local', 'testing'])) {
            $user = User::findOrFail($request->id);

            if (! $user->active) {
                return redirect('/inactive');
            }

            Auth::login($user);
            Session::regenerate();

            return redirect()->intended('/');
        }
    }

    public function destroy()
    {
        if (app()->environment(['local', 'testing'])) {
            Auth::logout();
            Session::flush();

            return redirect('/');
        }
    }
}
