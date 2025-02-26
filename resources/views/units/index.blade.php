@extends('layout')
@section('title', __('common.units'))

@section('subheader')
    <a class="hover:bg-gray-200 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded-sm"
        href="{{ route('units.create') }}">{{ __('units.add') }}</a>
@endsection

@section('content')

    <livewire:search-units />

@endsection
