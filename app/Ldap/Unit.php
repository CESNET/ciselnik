<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class Unit extends Model
{
    /**
     * The object classes of the LDAP model.
     */
    public static array $objectClasses = [
        'top',
        'dcObject',
        'cesnetOrganization',
    ];

    protected function getCreatableRdnAttribute(): string
    {
        return 'dc';
    }
}
