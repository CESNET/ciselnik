<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaiOrganization;
use App\Ldap\LaiOrganization;

class CesnetCaController extends Controller
{
    public function index()
    {
        return view('cesnet-ca.index');
    }

    public function create()
    {
        return view('cesnet-ca.create');
    }

    public function store(StoreLaiOrganization $request)
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
