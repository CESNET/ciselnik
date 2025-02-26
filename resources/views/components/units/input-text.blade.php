@props(['attribute', 'unit' => null, 'required' => true])

{!! $errors->first($attribute, '<div class="float-right text-sm font-semibold text-red-600">:message</div>') !!}
<input
    class="mb-2 w-full px-4 py-2 border rounded-lg shadow-sm @error($attribute) border-red-500 @else @if (old($attribute) !== null) border-green-500 @endif @enderror"
    type="text" name="{{ $attribute }}" id="{{ $attribute }}"
    value="{{ old($attribute, $unit?->getFirstAttribute($attribute)) }}"
    placeholder="{{ __("organizations.placeholder_$attribute") }}" @if ($required) required @endif>
