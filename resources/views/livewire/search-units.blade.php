<div>

    <div class="mb-4">
        <form>
            <label class="sr-only" for="search">{{ __('common.search') }}</label>
            <input wire:model.debounce.500ms="search" class="w-full px-4 py-2 border rounded-lg" type="text"
                name="search" id="search" placeholder="{{ __('organizations.search') }}" autofocus>
        </form>
        <div wire:loading class="font-bold">
            {{ __('units.loading_units_please_wait') }}
        </div>
    </div>

    <div>
        <div class="overflow-x-auto bg-white border rounded-lg">

            <table class="min-w-full border-b border-gray-300">

                <thead>
                    <tr>
                        <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.name') }}</th>
                        <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">&nbsp;
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse ($units as $unit)
                        <tr x-data class="hover:bg-blue-50 cursor-pointer"
                            @click="window.location = $el.querySelector('a').href">
                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                {{ $unit->getFirstAttribute('o;lang-cs') ?? $unit->getFirstAttribute('o') }}
                                <div class="text-xs text-gray-500">
                                    {{ $unit->getDn() }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                <a class="hover:underline text-blue-500"
                                    href="{{ route('units.show', $unit) }}">{{ __('common.detail') }}</a>
                            </td>
                        </tr>

                    @empty

                        <tr class="hover:bg-blue-50">
                            <td class="px-6 py-3 font-bold text-center" colspan="2">
                                {{ __('units.none_found') }}
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>
