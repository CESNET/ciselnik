<form action="{{ $action }}" method="POST">
    @csrf
    @method('patch')
    <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow-sm">
        <div>
            <dl>
                {{ $slot }}
            </dl>
        </div>
    </div>
</form>
