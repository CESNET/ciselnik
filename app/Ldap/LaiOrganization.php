<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class LaiOrganization extends Model
{
    protected $connection = 'ejbca';

    /**
     * *** IMPORTANT ***************************************************
     * eeeOrganization objectClass is required for Lai to work, however,
     * organizations created by EjbCA does NOT have this objectClass.
     * *****************************************************************
     */
    public static $objectClasses = [
        'top',
        'organization',
        'eeeOrganization',
    ];

    protected function getCreatableRdnAttribute()
    {
        return 'o';
    }
}
