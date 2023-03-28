@extends('layout')
@section('title', __('users.add'))

@section('content')

    <x-model-create action="{{ route('users.store') }}">
        <x-row>
            <x-slot:term>{{ __('common.name') }}</x-slot:term>
            {!! $errors->first('name', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
            <x-users.input-text field="name" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('common.uniqueid') }}</x-slot:term>
            {!! $errors->first('uniqueid', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
            <x-users.input-text field="uniqueid" />
        </x-row>
        <x-row>
            <x-slot:term>{{ __('common.email') }}</x-slot:term>
            {!! $errors->first('email', '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
            <x-users.input-text field="email" />
        </x-row>
        <div class="even:bg-gray-50 odd:bg-white px-4 py-5">
            <x-link-button href="{{ route('users.index') }}" text="{{ __('common.back') }}" />
            <x-button>{{ __('common.add') }}</x-button>
        </div>
    </x-model-create>

@endsection
