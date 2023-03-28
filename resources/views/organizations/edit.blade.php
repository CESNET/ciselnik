@extends('layout')
@section('title', $organization->getFirstAttribute('o;lang-en'))

@section('content')

    <x-model-edit action="{{ route('organizations.update', $organization) }}">
        <x-row>
            <x-slot:term>{{ __('ldap.o') }}</x-slot:term>
            <x-organizations.input-text attribute="o;lang-cs" :organization="$organization" />
            <x-organizations.input-text attribute="o;lang-en" :organization="$organization" :required="false" />
            <x-organizations.input-text attribute="o" :organization="$organization" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.oAbbrev') }}</x-slot:term>
            <x-organizations.input-text attribute="oabbrev;lang-cs" :organization="$organization" />
            <x-organizations.input-text attribute="oabbrev;lang-en" :organization="$organization" :required="false" />
            <x-organizations.input-text attribute="oabbrev" :organization="$organization" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.iCO') }}</x-slot:term>
            <x-organizations.input-text attribute="ico" :organization="$organization" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.street') }}</x-slot:term>
            <x-organizations.input-text attribute="street" :organization="$organization" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.l') }}</x-slot:term>
            <x-organizations.input-text attribute="l" :organization="$organization" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.postalCode') }}</x-slot:term>
            <x-organizations.input-text attribute="postalcode" :organization="$organization" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.c') }}</x-slot:term>
            <x-organizations.input-text attribute="c" :organization="$organization" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.labeledURI') }}</x-slot:term>
            <x-organizations.input-text attribute="labeleduri" :organization="$organization" />
        </x-row>
        <div class="odd:bg-gray-50 even:bg-white px-4 py-5">
            <x-link-button href="{{ route('organizations.index') }}" text="{{ __('common.back') }}" />
            <x-button>{{ __('common.update') }}</x-button>
        </div>
    </x-model-edit>

@endsection
