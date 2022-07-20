A new Unit has been just created:
- o: {{ $unit->getFirstAttribute('ou') }}

See details at {{ route('units.show', $unit) }}

Thanks,
{{ config('app.name') }}
