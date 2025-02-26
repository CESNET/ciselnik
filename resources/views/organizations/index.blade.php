@extends('layout')
@section('title', __('common.organizations'))

@section('subheader')
    <a class="hover:bg-gray-200 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded-sm"
        href="{{ route('organizations.create') }}">{{ __('organizations.add') }}</a>
@endsection

@section('content')

    <livewire:search-organizations />

@endsection
