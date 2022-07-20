@props(['objectClass'])

<span
    {{ $attributes->class([
        'px-2 text-xs font-semibold rounded-full',
        'text-blue-800 bg-blue-100' => in_array('eeeOrganization', $objectClass),
        'text-green-800 bg-green-100' => !in_array('eeeOrganization', $objectClass),
    ]) }}>
        {{ in_array('eeeOrganization', $objectClass) ? __('cesnet-ca.lai') : __('cesnet-ca.ejbca') }}
</span>