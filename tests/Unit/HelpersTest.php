<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    #[Test]
    public function ascii_characters_are_untouched(): void
    {
        $this->assertEquals('abcdefghijklmnopqrstuvwxyz', remove_accents('abcdefghijklmnopqrstuvwxyz'));
        $this->assertEquals('ABCDEFGHIJKLMNOPQRSTUVWXYZ', remove_accents('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
        $this->assertEquals('0123456789', remove_accents('0123456789'));
    }

    #[Test]
    public function remove_accents_function_removes_czech_accents_properly(): void
    {
        $this->assertEquals('escrzyaieuudtn', remove_accents('ěščřžýáíéúůďťň'));
        $this->assertEquals('ESCRZYAIEUUDTN', remove_accents('ĚŠČŘŽÝÁÍÉÚŮĎŤŇ'));
    }

    #[Test]
    public function remove_accents_function_removes_german_accents_properly(): void
    {
        $this->assertEquals('aeou', remove_accents('äëöü'));
        $this->assertEquals('AEOU', remove_accents('ÄËÖÜ'));
    }

    #[Test]
    public function remove_accents_function_removes_german_eszett_properly(): void
    {
        $this->assertEquals('s', remove_accents('ß'));
    }
}
