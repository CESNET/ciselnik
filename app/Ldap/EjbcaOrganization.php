<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class EjbcaOrganization extends Model
{
    protected $connection = 'ejbca';

    public static $objectClasses = [
        'top',
        'organization',
    ];
}
