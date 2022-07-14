<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class Organization extends Model
{
    public static $objectClasses = [
        'top',
        'dcObject',
        'cesnetOrganization',
    ];

    protected function getCreatableRdnAttribute()
    {
        return 'dc';
    }
}
