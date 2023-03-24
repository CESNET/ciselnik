@extends('layout')
@section('title', $user->name)

@section('content')

    <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
        <div>
            <dl>
                <x-row-top>{{ __('users.profile') }}</x-row-top>
                <x-row>
                    <x-slot:term>{{ __('common.name') }}</x-slot:term>
                    {{ $user->name }}
                    <div class="inline-block pl-4 space-x-4">
                        <x-pils.users-status :user="$user" />
                        <x-pils.users-role :user="$user" />
                    </div>
                </x-row>
                <x-row>
                    <x-slot:term>{{ __('common.uniqueid') }}</x-slot:term>
                    <code class="text-sm text-pink-500">{{ $user->uniqueid }}</code>
                </x-row>
                <x-row>
                    <x-slot:term>{{ __('common.email') }}</x-slot:term>
                    <a class="hover:underline text-blue-500" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                </x-row>
            </dl>
        </div>
    </div>

    <x-link-button href="{{ URL::previous() }}" text="{{ __('common.back') }}" />
    <x-forms.users-status :user="$user" />
    <x-forms.users-role :user="$user" />

@endsection
