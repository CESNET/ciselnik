@extends('layout')
@section('title', __('organizations.add_organization'))

@section('content')

    <form action="{{ route('organizations.store') }}" method="POST">
        @csrf
        <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
            <div>
                <dl>
                    <x-row>
                        <x-slot:term>{{ __('ldap.dc') }}</x-slot:term>
                        <x-organizations.input-text attribute="dc" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.o') }}</x-slot:term>
                        <x-organizations.input-text attribute="o;lang-cs" />
                        <x-organizations.input-text attribute="o;lang-en" :required="false" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.oAbbrev') }}</x-slot:term>
                        <x-organizations.input-text attribute="oabbrev;lang-cs" />
                        <x-organizations.input-text attribute="oabbrev;lang-en" :required="false" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.iCO') }}</x-slot:term>
                        <x-organizations.input-text attribute="ico" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.street') }}</x-slot:term>
                        <x-organizations.input-text attribute="street" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.l') }}</x-slot:term>
                        <x-organizations.input-text attribute="l" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.postalCode') }}</x-slot:term>
                        <x-organizations.input-text attribute="postalcode" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.c') }}</x-slot:term>
                        <x-organizations.input-text attribute="c" />
                    </x-row>
                    <x-row>
                        <x-slot:term>{{ __('ldap.labeledURI') }}</x-slot:term>
                        <x-organizations.input-text attribute="labeleduri" />
                    </x-row>
                    <div class="even:bg-gray-50 odd:bg-white px-4 py-5">
                        <x-link-button href="{{ route('organizations.index') }}" text="{{ __('common.back') }}" />
                        <x-button>{{ __('common.add') }}</x-button>
                    </div>
                </dl>
            </div>
    </form>

@endsection
