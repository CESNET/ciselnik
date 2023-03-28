A new Unit has been just created:
- ou: {{ $unit->getFirstAttribute('ou') }}

See details at {{ route('units.show', $unit) }}

Thanks,
{{ config('app.name') }}
