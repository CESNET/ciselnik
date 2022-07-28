<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganization;
use App\Http\Requests\UpdateOrganization;
use App\Ldap\Organization;
use App\Mail\OrganizationCreated;
use Illuminate\Support\Facades\Mail;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('organizations.index');
    }

    public function create()
    {
        return view('organizations.create');
    }

    public function store(StoreOrganization $request)
    {
        try {
            $organization = Organization::create(
                array_merge($request->validated(), [
                    'o' => remove_accents($request->validated('o;lang-cs')),
                    'oabbrev' => remove_accents($request->validated('oabbrev;lang-cs')),
                ])
            );

            Mail::to(config('mail.notify_new_object'))->send(new OrganizationCreated($organization));
        } catch (\LdapRecord\Exceptions\AlreadyExistsException) {
            abort(500, __('common.object_exists'));
        }

        return redirect()
            ->route('organizations.show', $organization)
            ->with('status', __('organizations.stored'));
    }

    public function show(Organization $organization)
    {
        if ($organization->getAttribute('oparentpointer')) {
            return redirect()
                ->route('units.show', $organization)
                ->with('status', __('units.redirected_from_organizations'));
        }

        return view('organizations.show', [
            'organization' => $organization,
        ]);
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', [
            'organization' => $organization,
        ]);
    }

    public function update(UpdateOrganization $request, Organization $organization)
    {
        $organization->save($request->validated());

        return redirect()
            ->route('organizations.show', $organization)
            ->with('status', __('organizations.updated'));
    }

    public function destroy(Organization $organization)
    {
        $this->authorize('everything');

        $dn = $organization->getDn();

        $organization->delete();

        return redirect()
            ->route('organizations.index')
            ->with('status', __('organizations.deleted', ['dn' => $dn]));
    }
}
