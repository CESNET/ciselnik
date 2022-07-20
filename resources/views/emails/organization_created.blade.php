A new Organization has been just created:
- o: {{ $organization->getFirstAttribute('o') }}

See details at {{ route('organizations.show', $organization) }}

Thanks,
{{ config('app.name') }}
