<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Ldap\Organization;
use App\Ldap\Unit;
use App\Mail\UnitCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('units.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('units.create', [
            'organizations' => Organization::whereNotHas('oparentpointer')->orderBy('o')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request): RedirectResponse
    {
        $dc = preg_replace('/dc=/', '', $request->oparentpointer);
        $organization = Organization::whereDc($dc)->firstOrFail();

        try {
            $unit = Unit::create(
                array_merge($request->validated(), [
                    'o' => remove_accents($request->validated('o;lang-cs')),
                    'oabbrev' => remove_accents($request->validated('ouabbrev;lang-cs')),
                    'ou' => remove_accents($request->validated('ou;lang-cs')),
                    'ouabbrev' => remove_accents($request->validated('ouabbrev;lang-cs')),
                    'oparentpointer' => $organization->getDn(),
                ])
            );

            Mail::send(new UnitCreated($unit));
        } catch (\LdapRecord\Exceptions\AlreadyExistsException) {
            $u = Unit::whereDc($request->validated('dc'))->first();

            return to_route('units.show', $u)
                ->with('status', __('organizations.already_exists'));
        }

        return redirect()
            ->route('units.show', $unit)
            ->with('status', __('units.stored'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit): View
    {
        return view('units.show', [
            'unit' => $unit,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit): View
    {
        return view('units.edit', [
            'unit' => $unit,
            'organizations' => Organization::whereNotHas('oparentpointer')->orderBy('o')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        $base_dn = config('ldap.connections.default.base_dn');
        $dc = preg_replace('/dc=/', '', $request->validated('oparentpointer'), 1);
        $dc = preg_replace("/,$base_dn/", '', $dc);
        $organization = Organization::whereDc($dc)->firstOrFail();

        $unit->save(
            array_merge($request->validated(), [
                'oparentpointer' => $organization->getDn(),
            ])
        );

        return redirect()
            ->route('units.show', $unit)
            ->with('status', __('units.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit): RedirectResponse
    {
        Gate::authorize('do-everything');

        $dn = $unit->getDn();

        $unit->delete();

        return redirect()
            ->route('units.index')
            ->with('status', __('units.deleted', ['dn' => $dn]));
    }
}
