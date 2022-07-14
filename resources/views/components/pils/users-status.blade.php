@props(['user'])

@unless($user->trashed())
    <span
        {{ $attributes->class([
            'px-2 text-xs font-semibold rounded-full',
            'text-green-800 bg-green-100' => $user->active === true,
            'text-red-800 bg-red-100' => $user->active === false,
        ]) }}>
        {{ $user->active ? __('common.active') : __('common.inactive') }}
    </span>
@else
    <span {{ $attributes->class(['px-2 text-xs font-semibold rounded-full', 'text-red-800 bg-red-100']) }}>
        {{ __('users.softdeleted') }}
    </span>
@endunless
