<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/StringHelper.php';

class StringHelperTest extends TestCase
{
    public function testTruncateReturnsOriginalWhenWithinLimit()
    {
        $this->assertEquals(
            "Hola",
            StringHelper::truncate("Hola", 10)
        );
    }

    public function testTruncateCutsTextAndAddsSuffix()
    {
        $this->assertEquals(
            "Hola...",
            StringHelper::truncate("Hola Mundo", 4)
        );
    }

    public function testToSlugConvertsCorrectly()
    {
        $this->assertEquals(
            "hola-mundo-2024",
            StringHelper::toSlug("¡Hola Mundo! 2024")
        );
    }

    public function testCountWordsCountsCorrectly()
    {
        $this->assertEquals(
            4,
            StringHelper::countWords("Hola mundo desde PHP")
        );
    }
}