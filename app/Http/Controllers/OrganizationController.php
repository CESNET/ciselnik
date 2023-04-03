<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Ldap\Organization;
use App\Mail\OrganizationCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(): View
    {
        return view('organizations.index');
    }

    public function create(): View
    {
        return view('organizations.create');
    }

    public function store(StoreOrganizationRequest $request): RedirectResponse
    {
        try {
            $organization = Organization::create(
                array_merge($request->validated(), [
                    'o' => remove_accents($request->validated('o;lang-cs')),
                    'oabbrev' => remove_accents($request->validated('oabbrev;lang-cs')),
                ])
            );

            Mail::send(new OrganizationCreated($organization));
        } catch (\LdapRecord\Exceptions\AlreadyExistsException) {
            abort(500, __('common.object_exists'));
        }

        return redirect()
            ->route('organizations.show', $organization)
            ->with('status', __('organizations.stored'));
    }

    public function show(Organization $organization): View|RedirectResponse
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

    public function edit(Organization $organization): View
    {
        return view('organizations.edit', [
            'organization' => $organization,
        ]);
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $organization->save($request->validated());

        return redirect()
            ->route('organizations.show', $organization)
            ->with('status', __('organizations.updated'));
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        $this->authorize('everything');

        $dn = $organization->getDn();

        $organization->delete();

        return redirect()
            ->route('organizations.index')
            ->with('status', __('organizations.deleted', ['dn' => $dn]));
    }
}
