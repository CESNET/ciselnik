@extends('layout')
@section('title', __('units.add_unit'))

@section('content')

    <form action="{{ route('units.store') }}" method="POST">
        @csrf
        <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
            <div>
                <dl>
                    <x-row>
                        <x-slot:term>{{ __('ldap.dc') }}</x-slot:term>
                        <x-units.input-text attribute="dc" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.o') }}</x-slot:term>
                        <x-units.input-text attribute="o;lang-cs" />
                        <x-units.input-text attribute="o;lang-en" :required="false" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.oAbbrev') }}</x-slot:term>
                        <x-units.input-text attribute="oabbrev;lang-cs" />
                        <x-units.input-text attribute="oabbrev;lang-en" :required="false" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.ou') }}</x-slot:term>
                        <x-units.input-text attribute="ou;lang-cs" />
                        <x-units.input-text attribute="ou;lang-en" :required="false" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.ouAbbrev') }}</x-slot:term>
                        <x-units.input-text attribute="ouabbrev;lang-cs" />
                        <x-units.input-text attribute="ouabbrev;lang-en" :required="false" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.oParentPointer') }}</x-slot:term>
                        {!! $errors->first('oparentpointer', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
                        <input
                            class="w-full px-4 py-2 border rounded-lg shadow @error('oparentpointer') border-red-500 @else @if (old('oparentpointer') !== null) border-green-500 @endif @enderror"
                            type="text" name="oparentpointer" id="oparentpointer" list="parentorganizations"
                            value="{{ old('oparentpointer') }}"
                            placeholder="{{ __('organizations.placeholder_oparentpointer') }}" required>
                        <datalist id="parentorganizations">
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->getRdn() }}">
                                    {{ $organization->getFirstAttribute('o') }}</option>
                            @endforeach
                        </datalist>
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.street') }}</x-slot:term>
                        <x-units.input-text attribute="street" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.l') }}</x-slot:term>
                        <x-units.input-text attribute="l" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.postalCode') }}</x-slot:term>
                        <x-units.input-text attribute="postalcode" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.c') }}</x-slot:term>
                        <x-units.input-text attribute="c" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.labeledURI') }}</x-slot:term>
                        <x-units.input-text attribute="labeleduri" />
                    </x-row>
                    <div class="even:bg-gray-50 odd:bg-white px-4 py-5">
                        <x-link-button href="{{ route('organizations.index') }}" text="{{ __('common.back') }}" />
                        <x-button>{{ __('common.add') }}</x-button>
                    </div>
                </dl>
            </div>
    </form>

@endsection
