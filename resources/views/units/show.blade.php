@extends('layout')
@section('title', $unit->getFirstAttribute('o;lang-en'))

@section('content')

    <x-model-show>
        <x-row-top>{{ __('common.detail') }}</x-row-top>
        <x-row>
            <x-slot:term>{{ __('ldap.dn') }}</x-slot:term>
            <code class="text-pink-500">{{ $unit->getDn() }}</code>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.o') }}</x-slot:term>
            <x-slot:desc>{{ __('common.cesky_english_ascii') }}</x-slot:desc>
            <div>{{ $unit->getFirstAttribute('o;lang-cs') }}</div>
            <div>{{ $unit->getFirstAttribute('o;lang-en') }}</div>
            <div>{{ $unit->getFirstAttribute('o') }}</div>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.oAbbrev') }}</x-slot:term>
            <x-slot:desc>{{ __('common.cesky_english_ascii') }}</x-slot:desc>
            <div>{{ $unit->getFirstAttribute('oAbbrev;lang-cs') }}</div>
            <div>{{ $unit->getFirstAttribute('oAbbrev;lang-en') }}</div>
            <div>{{ $unit->getFirstAttribute('oAbbrev') }}</div>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.ou') }}</x-slot:term>
            <x-slot:desc>{{ __('common.cesky_english_ascii') }}</x-slot:desc>
            <div>{{ $unit->getFirstAttribute('ou;lang-cs') }}</div>
            <div>{{ $unit->getFirstAttribute('ou;lang-en') }}</div>
            <div>{{ $unit->getFirstAttribute('ou') }}</div>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.ouAbbrev') }}</x-slot:term>
            <x-slot:desc>{{ __('common.cesky_english_ascii') }}</x-slot:desc>
            <div>{{ $unit->getFirstAttribute('ouAbbrev;lang-cs') }}</div>
            <div>{{ $unit->getFirstAttribute('ouAbbrev;lang-en') }}</div>
            <div>{{ $unit->getFirstAttribute('ouAbbrev') }}</div>
        </x-row>
        <x-row x-data="" @click="window.location = $el.querySelector('a').href" class="cursor-pointer">
            <x-slot:term>{{ __('ldap.oParentPointer') }}</x-slot:term>
            <div>
                <a class="hover:underline text-blue-500"
                    href="{{ route('organizations.show', \App\Ldap\Organization::findOrFail($unit->getFirstAttribute('oParentPointer'))) }}">{{ $unit->getFirstAttribute('oParentPointer') }}</a>
            </div>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.street') }}</x-slot:term>
            {{ $unit->getFirstAttribute('street') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.l') }}</x-slot:term>
            {{ $unit->getFirstAttribute('l') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.postalCode') }}</x-slot:term>
            {{ $unit->getFirstAttribute('postalCode') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.c') }}</x-slot:term>
            {{ $unit->getFirstAttribute('c') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.labeledURI') }}</x-slot:term>
            <a class="hover:underline text-blue-500"
                href="{{ $unit->getFirstAttribute('labeledURI') }}">{{ $unit->getFirstAttribute('labeledURI') }}</a>
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.cesnetOrgID') }}</x-slot:term>
            {{ $unit->getFirstAttribute('cesnetOrgID') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.cesnetActive') }}</x-slot:term>
            {{ $unit->getFirstAttribute('cesnetActive') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.cesnetMember') }}</x-slot:term>
            {{ $unit->getFirstAttribute('cesnetMember') ?? __('common.empty') }}
        </x-row>
        <x-row>
            <x-slot:term>{{ __('ldap.cesnetVIP') }}</x-slot:term>
            {{ $unit->getFirstAttribute('cesnetVIP') ?? __('common.empty') }}
        </x-row>
        <div class="even:bg-gray-50 odd:bg-white px-4 py-5">
            <x-link-button href="{{ URL::previous() }}" text="{{ __('common.back') }}" />
            <x-link-button href="{{ route('units.edit', $unit) }}" text="{{ __('common.edit') }}" color="yellow" />
            @can('everything')
                <form class="inline-block" action="{{ route('units.destroy', $unit) }}" method="POST">
                    @csrf
                    @method('delete')
                    <x-button color="red">{{ __('common.delete') }}</x-button>
                </form>
            @endcan
        </div>
    </x-model-show>

@endsection
