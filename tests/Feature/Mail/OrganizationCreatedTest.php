<?php

namespace Tests\Feature\Mail;

use App\Ldap\Organization;
use App\Mail\OrganizationCreated;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Str;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrganizationCreatedTest extends TestCase
{
    #[Test]
    public function organization_created_notification_email_check(): void
    {
        DirectoryEmulator::setup('default');

        $organization = Organization::create([
            'dc' => fake()->word(),
            'o' => Str::remove("'", fake()->company()),
            'o;lang-cs' => fake()->company(),
            'oabbrev;lang-cs' => fake()->word(),
            'ico' => fake()->randomNumber(),
            'street' => fake()->streetAddress(),
            'l' => fake()->city(),
            'postalcode' => fake()->randomNumber(),
            'c' => fake()->stateAbbr(),
            'labeleduri' => fake()->url(),
        ]);

        $mailable = new OrganizationCreated($organization);

        $mailable->assertHasSubject(config('app.name').': Organization Created');
        $mailable->assertHasReplyTo([
            new Address(
                config('mail.reply_to.address'),
                config('mail.reply_to.name'),
            ),
        ]);
        $mailable->assertSeeInOrderInHtml([
            'A new Organization has been just created',
            $organization->getFirstAttribute('o'),
            'See details at',
            route('organizations.show', $organization),
        ]);
        $mailable->assertSeeInOrderInText([
            'A new Organization has been just created',
            $organization->getFirstAttribute('o'),
            'See details at',
            route('organizations.show', $organization),
        ]);

        DirectoryEmulator::tearDown();
    }
}
