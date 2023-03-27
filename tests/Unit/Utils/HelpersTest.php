<?php

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function remove_accents_function_removes_czech_accents_properly()
    {
        $this->assertEquals('escrzyaieuudtn', remove_accents('ěščřžýáíéúůďťň'));
        $this->assertEquals('ESCRZYAIEUUDTN', remove_accents('ĚŠČŘŽÝÁÍÉÚŮĎŤŇ'));
    }

    /** @test */
    public function remove_accents_function_removes_german_accents_properly()
    {
        $this->assertEquals('aeou', remove_accents('äëöü'));
        $this->assertEquals('AEOU', remove_accents('ÄËÖÜ'));
    }

    /** @test */
    public function remove_accents_function_removes_german_eszett_properly()
    {
        $this->assertEquals('s', remove_accents('ß'));
    }
}
