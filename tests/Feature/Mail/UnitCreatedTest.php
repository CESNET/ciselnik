<?php

namespace Tests\Feature\Mail;

use App\Ldap\Organization;
use App\Ldap\Unit;
use App\Mail\UnitCreated;
use Illuminate\Support\Str;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Mime\Address;
use Tests\TestCase;

class UnitCreatedTest extends TestCase
{
    #[Test]
    public function unit_created_notification_email_check(): void
    {
        DirectoryEmulator::setup('default');

        $organization = Organization::create([
            'dc' => fake()->word(),
            'o;lang-cs' => fake()->company(),
            'oabbrev;lang-cs' => fake()->word(),
            'ico' => fake()->randomNumber(),
            'street' => fake()->streetAddress(),
            'l' => fake()->city(),
            'postalcode' => fake()->randomNumber(),
            'c' => fake()->stateAbbr(),
            'labeleduri' => fake()->url(),
        ]);
        $unit = Unit::create([
            'dc' => fake()->word(),
            'o;lang-cs' => fake()->company(),
            'oabbrev;lang-cs' => fake()->word(),
            'ou;lang-cs' => fake()->company(),
            'ou' => Str::remove("'", fake()->company()),
            'ouabbrev;lang-cs' => fake()->word(),
            'oparentpointer' => $organization->getRdn(),
            'street' => fake()->streetAddress(),
            'l' => fake()->city(),
            'postalcode' => fake()->randomNumber(),
            'c' => fake()->stateAbbr(),
            'labeleduri' => fake()->url(),
        ]);

        $mailable = new UnitCreated($unit);

        $mailable->assertHasSubject(config('app.name').': Unit Created');
        $mailable->assertHasReplyTo([
            new Address(
                config('mail.reply_to.address'),
                config('mail.reply_to.name'),
            ),
        ]);
        $mailable->assertSeeInOrderInHtml([
            'A new Unit has been just created',
            $unit->getFirstAttribute('ou'),
            'See details at',
            route('units.show', $unit),
        ]);
        $mailable->assertSeeInOrderInText([
            'A new Unit has been just created',
            $unit->getFirstAttribute('ou'),
            'See details at',
            route('units.show', $unit),
        ]);

        DirectoryEmulator::tearDown();
    }
}
