<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaiOrganizationRequest;
use App\Ldap\LaiOrganization;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CesnetCaController extends Controller
{
    public function index(): View
    {
        return view('cesnet-ca.index');
    }

    public function create(): View
    {
        return view('cesnet-ca.create');
    }

    public function store(StoreLaiOrganizationRequest $request): RedirectResponse
    {
        try {
            LaiOrganization::create($request->validated());
        } catch (\LdapRecord\Exceptions\AlreadyExistsException) {
            abort(500, __('common.object_exists'));
        }

        return redirect()
            ->route('cesnet-ca.index')
            ->with('status', __('organizations.stored'));
    }
}
