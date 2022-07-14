@extends('layout')
@section('title', $organization->getFirstAttribute('o;lang-en'))

@section('content')

    <h3 class="text-lg font-semibold">{{ __('common.detail') }}</h3>

    <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
        <div>
            <dl>
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
                    {{ $organization->getFirstAttribute('ico') }}
                </x-row>
                <x-row>
                    <x-slot:term>{{ __('ldap.street') }}</x-slot:term>
                    {{ $organization->getFirstAttribute('street') }}
                </x-row>
                <x-row>
                    <x-slot:term>{{ __('ldap.l') }}</x-slot:term>
                    {{ $organization->getFirstAttribute('l') }}
                </x-row>
                <x-row>
                    <x-slot:term>{{ __('ldap.postalCode') }}</x-slot:term>
                    {{ $organization->getFirstAttribute('postalcode') }}
                </x-row>
                <x-row>
                    <x-slot:term>{{ __('ldap.c') }}</x-slot:term>
                    {{ $organization->getFirstAttribute('c') }}
                </x-row>
                <x-row>
                    <x-slot:term>{{ __('ldap.labeledURI') }}</x-slot:term>
                    <a class="hover:underline text-blue-500"
                        href="{{ $organization->getFirstAttribute('labeleduri') }}">{{ $organization->getFirstAttribute('labeledURI') }}</a>
                </x-row>
                <div class="odd:bg-gray-50 even:bg-white px-4 py-5">
                    <x-link-button href="{{ URL::previous() }}" text="{{ __('common.back') }}" />
                    <x-link-button href="{{ route('organizations.edit', $organization) }}"
                        text="{{ __('common.edit') }}" color="yellow" />
                    @can('everything')
                        <form class="inline-block" action="{{ route('organizations.destroy', $organization) }}"
                            method="POST">
                            @csrf
                            @method('delete')
                            <x-button color="red">{{ __('common.delete') }}</x-button>
                        </form>
                    @endcan
                </div>
            </dl>
        </div>
    </div>

@endsection
