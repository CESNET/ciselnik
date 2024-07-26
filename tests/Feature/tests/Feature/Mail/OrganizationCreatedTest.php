<?php

namespace Tests\Feature\tests\Feature\Mail;

use Tests\TestCase;

class OrganizationCreatedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
