<?php

namespace MyApp;

class MathHelper
{
    public static function add(int $a, int $b): int
    {
        return $a + $b;
    }

    public static function divide(float $a, float $b): float
    {
        if ($b == 0) {
            throw new \RuntimeException("No se puede dividir entre cero.");
        }
        return $a / $b;
    }
}
