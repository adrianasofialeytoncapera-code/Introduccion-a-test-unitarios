<?php

use MyApp\MathHelper;
use MyTests\TestRunner;

return function (TestRunner $t): void {

    $t->suite('MathHelperTests', function (TestRunner $t): void {

        $t->test('Add_TwoPositiveNumbers_ReturnsCorrectSum', function (TestRunner $t): void {
            // Arrange
            $a = 2; $b = 3;

            // Act
            $result = MathHelper::add($a, $b);

            // Assert
            $t->assertEquals(5, $result);
        });

        $t->test('Divide_ReturnsCorrectQuotient', function (TestRunner $t): void {
            $result = MathHelper::divide(10, 4);
            $t->assertEqualsFloat(2.5, $result, 2);
        });

        $t->test('Divide_ByZero_ThrowsDivideByZeroError', function (TestRunner $t): void {
            $t->assertThrows(\RuntimeException::class, fn() => MathHelper::divide(5, 0));
        });

    });

};
