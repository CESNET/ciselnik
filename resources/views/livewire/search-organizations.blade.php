<div>

    <x-searchbox models="organizations" />

    <x-main-div>

        <x-table>

            <x-slot:thead>
                <x-th>{{ __('common.name') }}</x-th>
                <x-th>&nbsp;</x-th>
            </x-slot:thead>

            @forelse ($organizations as $organization)
                <x-tr>
                    <x-td>
                        <div>
                            {{ $organization->getFirstAttribute('o;lang-cs') ?? $organization->getFirstAttribute('o') }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $organization->getDn() }}</div>
                    </x-td>
                    <x-td>
                        <x-a href="{{ route('organizations.show', $organization) }}">{{ __('common.show') }}</x-a>
                    </x-td>
                </x-tr>
            @empty
                <x-tr>
                    <x-td colspan="2">
                        {{ __('organizations.none_found') }}
                    </x-td>
                </x-tr>
            @endforelse
        </x-table>

    </x-main-div>

</div>
