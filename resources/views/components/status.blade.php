@props(['color' => 'green'])

<span
    {{ $attributes->class([
        'px-2 text-xs font-semibold rounded-full',
        'dark:bg-green-800 dark:text-green-100  text-green-800 bg-green-100' => $color === 'green',
        'dark:bg-red-800 dark:text-red-100 text-red-800 bg-red-100' => $color === 'red',
    ]) }}>{{ $slot }}</span>
