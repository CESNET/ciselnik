<div>

    @if (!empty($search) or $users->count() > 5)
        <div class="mb-4">
            <label class="sr-only" for="search">{{ __('common.search') }}</label>
            <input wire:model.debounce.500ms="search" class="w-full px-4 py-2 border rounded-lg" type="text"
                name="search" id="search" placeholder="{{ __('users.search') }}" autofocus>
            <div wire:loading class="font-bold">
                {{ __('users.loading_users_please_wait') }}
            </div>
        </div>
    @endif

    <div>
        <div class="overflow-x-auto bg-white border rounded-lg">

            <table class="min-w-full border-b border-gray-100">

                <thead>
                    <tr>
                        <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.name') }}</th>
                        <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.email') }}</th>
                        <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">
                            {{ __('common.status') }}</th>
                        <th class="px-6 py-3 text-xs tracking-widest text-left uppercase bg-gray-100 border-b">&nbsp;
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-300">

                    @forelse ($users as $user)
                        <tr x-data class="hover:bg-blue-50 cursor-pointer"
                            @click="window.location = $el.querySelector('a.link').href">
                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                <div class="font-bold">{{ $user->name }}</div>
                                <div class="text-gray-400">{{ $user->uniqueid }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm"><a class="hover:underline text-blue-500"
                                    href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                <div>
                                    <x-pils.users-status :user="$user" />
                                </div>
                                <div>
                                    <x-pils.users-role :user="$user" />
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm"><a
                                    class="hover:underline link text-blue-500"
                                    href="{{ route('users.show', $user) }}">{{ __('common.detail') }}</a></td>
                        </tr>
                    @empty
                        <tr class="hover:bg-blue-50 cursor-pointer">
                            <td class="whitespace-nowrap px-6 py-3 font-semibold text-center" colspan="4">
                                {{ __('users.none_found') }}
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

            {{ $users->links() }}

        </div>
    </div>

</div>
