<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnit;
use App\Http\Requests\UpdateUnit;
use App\Ldap\Organization;
use App\Ldap\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('units.index');
    }

    public function create()
    {
        return view('units.create', [
            'organizations' => Organization::whereNotHas('oparentpointer')->orderBy('o')->get(),
        ]);
    }

    public function store(StoreUnit $request)
    {
        $dc = preg_replace('/dc=/', '', $request->oparentpointer);
        $organization = Organization::whereDc($dc)->firstOrFail();

        $unit = Unit::create(
            array_merge($request->validated(), [
                'o' => remove_accents($request->validated('o;lang-cs')),
                'oabbrev' => remove_accents($request->validated('ouabbrev;lang-cs')),
                'ou' => remove_accents($request->validated('ou;lang-cs')),
                'ouabbrev' => remove_accents($request->validated('ouabbrev;lang-cs')),
                'oparentpointer' => $organization->getDn(),
            ])
        );

        return redirect()
            ->route('units.show', $unit)
            ->with('status', __('units.stored'));
    }

    public function show(Unit $unit)
    {
        return view('units.show', [
            'unit' => $unit,
        ]);
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', [
            'unit' => $unit,
            'organizations' => Organization::whereNotHas('oparentpointer')->orderBy('o')->get(),
        ]);
    }

    public function update(UpdateUnit $request, Unit $unit)
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

    public function destroy(Unit $unit)
    {
        $this->authorize('everything');

        $dn = $unit->getDn();

        $unit->delete();

        return redirect()
            ->route('units.index')
            ->with('status', __('units.deleted', ['dn' => $dn]));
    }
}
