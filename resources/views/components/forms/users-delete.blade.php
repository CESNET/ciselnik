@unless($user->active)

    @unless(Auth::id() === $user->id)

        @unless($user->trashed())
            <form class="inline-block" action="{{ route('users.destroy', $user) }}" method="POST">
                @csrf
                @method('delete')
                <x-button color="red">
                    {{ __('common.delete') }}
                </x-button>
            </form>
        @else
            <form class="inline-block" action="{{ route('users.restore', $user) }}" method="POST">
                @csrf
                @method('patch')
                <x-button color="green">
                    {{ __('common.restore') }}
                </x-button>
            </form>
        @endunless

    @endunless

@endunless
