<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../src/MathHelper.php';

class MathHelperTest extends TestCase
{
    public function test_add_returns_correct_sum(): void
    {
        $a = 2;
        $b = 3;

        $result = add($a, $b);

        $this->assertEquals(5, $result);
    }

    public function test_divide_returns_float(): void
    {
        $this->assertEquals(2.5, divide(5, 2));
    }

    public function test_divide_by_zero_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No se puede dividir entre cero.');

        divide(10, 0);
    }
}
