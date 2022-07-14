@extends('layout')
@section('title', $unit->getFirstAttribute('o;lang-en'))

@section('content')

    <form action="{{ route('units.update', $unit) }}" method="POST">
        @csrf
        @method('patch')
        <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
            <div>
                <dl>
                    <x-row>
                        <x-slot:term>{{ __('ldap.o') }}</x-slot:term>
                        <x-units.input-text attribute="o;lang-cs" :unit="$unit" />
                        <x-units.input-text attribute="o;lang-en" :unit="$unit" :required="false" />
                        <x-units.input-text attribute="o" :unit="$unit" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.oAbbrev') }}</x-slot:term>
                        <x-units.input-text attribute="oabbrev;lang-cs" :unit="$unit" />
                        <x-units.input-text attribute="oabbrev;lang-en" :unit="$unit" :required="false" />
                        <x-units.input-text attribute="oabbrev" :unit="$unit" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.ou') }}</x-slot:term>
                        <x-units.input-text attribute="ou;lang-cs" :unit="$unit" />
                        <x-units.input-text attribute="ou;lang-en" :unit="$unit" :required="false" />
                        <x-units.input-text attribute="ou" :unit="$unit" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.ouAbbrev') }}</x-slot:term>
                        <x-units.input-text attribute="ouabbrev;lang-cs" :unit="$unit" />
                        <x-units.input-text attribute="ouabbrev;lang-en" :unit="$unit" :required="false" />
                        <x-units.input-text attribute="ouabbrev" :unit="$unit" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.oParentPointer') }}</x-slot:term>
                        <input class="w-full px-4 py-2 mb-2 border rounded-lg shadow" type="text" name="oparentpointer"
                            id="oparentpointer" list="parentorganizations"
                            value="{{ old('oparentpointer', $unit->getFirstAttribute('oparentpointer')) }}" required>
                        <datalist id="parentorganizations">
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->getRdn() }}">
                                    {{ $organization->getFirstAttribute('o') }}</option>
                            @endforeach
                        </datalist>
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.street') }}</x-slot:term>
                        <x-units.input-text attribute="street" :unit="$unit" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.l') }}</x-slot:term>
                        <x-units.input-text attribute="l" :unit="$unit" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.postalCode') }}</x-slot:term>
                        <x-units.input-text attribute="postalcode" :unit="$unit" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.c') }}</x-slot:term>
                        <x-units.input-text attribute="c" :unit="$unit" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.labeledURI') }}</x-slot:term>
                        <x-units.input-text attribute="labeleduri" :unit="$unit" />
                    </x-row>
                    <div class="odd:bg-gray-50 even:bg-white px-4 py-5">
                        <x-link-button href="{{ route('units.index') }}" text="{{ __('common.back') }}" />
                        <x-button>{{ __('common.update') }}</x-button>
                    </div>
                </dl>
            </div>
    </form>

@endsection
