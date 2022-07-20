@extends('layout')
@section('title', __('common.add'))

@section('content')

    <form action="{{ route('cesnet-ca.store') }}" method="POST">
        @csrf
        <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
            <div>
                <dl>
                    <x-row>
                        <x-slot:term>{{ __('ldap.o') }}</x-slot:term>
                        <x-organizations.input-text attribute="o" />
                    </x-row>
                    <div class="even:bg-gray-50 odd:bg-white px-4 py-5">
                        <x-link-button href="{{ route('cesnet-ca.index') }}" text="{{ __('common.back') }}" />
                        <x-button>{{ __('common.add') }}</x-button>
                    </div>
                </dl>
            </div>
        </div>
    </form>

@endsection