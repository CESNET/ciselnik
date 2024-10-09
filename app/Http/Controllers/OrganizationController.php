<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Ldap\Organization;
use App\Mail\OrganizationCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('organizations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
            $o = Organization::whereDc($request->validated('dc'))->first();

            if (! is_null($o->getAttribute('oparentpointer'))) {
                return to_route('units.show', $o)
                    ->with('status', __('organizations.already_exists'));
            }

            return to_route('organizations.show', $o)
                ->with('status', __('organizations.already_exists'));
        }

        return redirect()
            ->route('organizations.show', $organization)
            ->with('status', __('organizations.stored'));
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization): View
    {
        return view('organizations.edit', [
            'organization' => $organization,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $organization->save($request->validated());

        return redirect()
            ->route('organizations.show', $organization)
            ->with('status', __('organizations.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization): RedirectResponse
    {
        Gate::authorize('do-everything');

        $dn = $organization->getDn();

        $organization->delete();

        return redirect()
            ->route('organizations.index')
            ->with('status', __('organizations.deleted', ['dn' => $dn]));
    }
}
