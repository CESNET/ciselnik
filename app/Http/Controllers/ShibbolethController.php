<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ShibbolethController extends Controller
{
    public function create(): RedirectResponse|string
    {
        if (is_null(request()->server('Shib-Handler'))) {
            return 'login';
        }

        return redirect(
            request()
                ->server('Shib-Handler')
                .'/Login?target='
                .action('\\'.__CLASS__.'@store')
        );
    }

    public function store(): RedirectResponse|View
    {
        $mail = explode(';', request()->server('mail'));

        $user = User::updateOrCreate(
            ['uniqueid' => request()->server('uniqueId')],
            [
                'name' => request()->server('cn'),
                'email' => $mail[0],
                'emails' => count($mail) > 1 ? request()->server('mail') : null,
            ]
        );

        if ($user->wasRecentlyCreated) {
            Log::channel('slack')->critical("A new account for {$user->name} is wating for an approval!");

            // send e-mail notification
            // after activating the account, they should be notified by email
            // show the user information that their account is waiting for an activation
            return view('account_created');
        }

        $user->refresh();

        if (! $user->active) {
            return view('inactive');
        }

        Auth::login($user);
        Session::regenerate();

        return redirect()->intended('/');
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();
        Session::flush();

        return redirect('/Shibboleth.sso/Logout');
    }
}
