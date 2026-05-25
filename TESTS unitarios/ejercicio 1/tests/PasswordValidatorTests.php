<?php

use MyApp\PasswordValidator;
use MyTests\TestRunner;

return function (TestRunner $t): void {

    $t->suite('PasswordValidatorTests', function (TestRunner $t): void {

        // Theory: IsValid con múltiples casos (equivalente a [Theory][InlineData])
        $t->theory('IsValid_VariousPasswords_ReturnsExpected', [
            ['Ab1',      false],   // muy corta
            ['abcdef12', false],   // sin mayúscula
            ['AbcdefGH', false],   // sin número
            ['Secret12', true],    // válida
        ], function (TestRunner $t, string $password, bool $expected): void {
            $validator = new PasswordValidator();
            $result = $validator->isValid($password);
            $t->assertEquals($expected, $result);
        });

        // Theory: GetStrength
        $t->theory('GetStrength_ReturnsCorrectLevel', [
            ['Abc12345',        'medium'],
            ['MyP@ssw0rd!2024', 'strong'],
            ['simple',          'weak'],
        ], function (TestRunner $t, string $password, string $expectedStrength): void {
            $validator = new PasswordValidator();
            $strength = $validator->getStrength($password);
            $t->assertEquals($expectedStrength, $strength);
        });

    });

};
