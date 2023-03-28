<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        // due to LdapRecord issues
        $_ENV['LDAP_LOGGING'] = false;

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
