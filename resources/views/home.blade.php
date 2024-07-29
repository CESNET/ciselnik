@extends('layout')
@section('title', __('common.dashboard'))

@section('content')

    <p class="mb-6">
        {!! __('welcome.introduction') !!}
    </p>

    <p>
        {!! __('welcome.contact') !!} <x-a href="mailto:info@eduid.cz">info@eduid.cz</x-a>
    </p>

@endsection
