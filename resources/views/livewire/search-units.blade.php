<div>

    <x-searchbox models="units" />

    <x-main-div>

        <x-table>

            <x-slot:thead>
                <x-th>{{ __('common.name') }}</x-th>
                <x-th>&nbsp;</x-th>
            </x-slot:thead>

            @forelse ($units as $unit)
                <x-tr>
                    <x-td>
                        <div>{{ $unit->getFirstAttribute('o;lang-cs') ?? $unit->getFirstAttribute('o') }}</div>
                        <div class="text-xs text-gray-500">{{ $unit->getDn() }}</div>
                    </x-td>
                    <x-td>
                        <x-a href="{{ route('units.show', $unit) }}">{{ __('common.show') }}</x-a>
                    </x-td>
                </x-tr>
            @empty
                <x-tr>
                    <x-td colspan="2">
                        {{ __('units.none_found') }}
                    </x-td>
                </x-tr>
            @endforelse

        </x-table>

    </x-main-div>

</div>
