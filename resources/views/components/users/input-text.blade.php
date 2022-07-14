@props(['field'])

<input
    class="mb-2 w-full px-4 py-2 border rounded-lg shadow @error($field) border-red-500 @else @if (old($field) !== null) border-green-500 @endif @enderror"
    type="text" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}"
    placeholder="{{ __("users.placeholder_$field") }}">
