@props(['models'])

<div class="mb-4">
    <form>
        <label class="sr-only" for="search">{{ __('common.search') }}</label>
        <input wire:model.debounce.500ms="search" class="w-full px-4 py-2 border rounded-lg" type="text" name="search"
            id="search" placeholder="{{ __("inputs.searchbox_{$models}") }}" autofocus>
    </form>
    <div wire:loading class="font-bold">
        {{ __("inputs.please_wait_loading_{$models}") }}
    </div>
</div>
