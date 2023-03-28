@extends('layout')
@section('title', $organization->getFirstAttribute('o;lang-en'))

@section('content')

    <x-model-show>
        <x-row-top>{{ __('common.detail') }}</x-row-top>
        <x-row>
            <x-slot:term>{{ __('ldap.dn') }}</x-slot:term>
            <code class="text-pink-500">{{ $organization->getDn() }}</code>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.o') }}</x-slot:term>
            <x-slot:desc>{{ __('common.cesky_english_ascii') }}</x-slot:desc>
            <div>{{ $organization->getFirstAttribute('o;lang-cs') }}</div>
            <div>{{ $organization->getFirstAttribute('o;lang-en') }}</div>
            <div>{{ $organization->getFirstAttribute('o') }}</div>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.oAbbrev') }}</x-slot:term>
            <x-slot:desc>{{ __('common.cesky_english_ascii') }}</x-slot:desc>
            <div>{{ $organization->getFirstAttribute('oabbrev;lang-cs') }}</div>
            <div>{{ $organization->getFirstAttribute('oabbrev;lang-en') }}</div>
            <div>{{ $organization->getFirstAttribute('oabbrev') }}</div>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.iCO') }}</x-slot:term>
            {{ $organization->getFirstAttribute('ico') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.street') }}</x-slot:term>
            {{ $organization->getFirstAttribute('street') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.l') }}</x-slot:term>
            {{ $organization->getFirstAttribute('l') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.postalCode') }}</x-slot:term>
            {{ $organization->getFirstAttribute('postalcode') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.c') }}</x-slot:term>
            {{ $organization->getFirstAttribute('c') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.labeledURI') }}</x-slot:term>
            <a class="hover:underline text-blue-500"
                href="{{ $organization->getFirstAttribute('labeleduri') }}">{{ $organization->getFirstAttribute('labeledURI') }}</a>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.cesnetOrgID') }}</x-slot:term>
            {{ $organization->getFirstAttribute('cesnetOrgID') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.cesnetActive') }}</x-slot:term>
            {{ $organization->getFirstAttribute('cesnetActive') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.cesnetMember') }}</x-slot:term>
            {{ $organization->getFirstAttribute('cesnetMember') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.cesnetVIP') }}</x-slot:term>
            {{ $organization->getFirstAttribute('cesnetVIP') ?? __('common.empty') }}
        </x-row>
        <div class="even:bg-gray-50 odd:bg-white px-4 py-5">
            <x-link-button href="{{ URL::previous() }}" text="{{ __('common.back') }}" />
            <x-link-button href="{{ route('organizations.edit', $organization) }}" text="{{ __('common.edit') }}"
                color="yellow" />
            @can('everything')
                <form class="inline-block" action="{{ route('organizations.destroy', $organization) }}" method="POST">
                    @csrf
                    @method('delete')
                    <x-button color="red">{{ __('common.delete') }}</x-button>
                </form>
            @endcan
        </div>
    </x-model-show>

@endsection
