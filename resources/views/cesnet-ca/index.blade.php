@extends('layout')
@section('title', __('common.cesnet-ca'))

@section('subheader')
    <a class="hover:bg-gray-200 px-2 py-1 text-sm bg-gray-300 border border-gray-400 rounded"
        href="{{ route('cesnet-ca.create') }}">{{ __('cesnet-ca.add') }}</a>
@endsection

@section('content')

    @livewire('search-cesnet-ca')

@endsection
