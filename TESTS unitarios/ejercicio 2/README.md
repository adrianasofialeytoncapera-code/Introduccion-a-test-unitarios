# PHPUnit Examples

Proyecto de ejemplo con los 4 pasos progresivos de testing en PHP puro (sin Laravel).

## Requisitos

- PHP 8.1+
- Composer

## Instalación

```bash
composer install
```

## Ejecutar todos los tests

```bash
./vendor/bin/phpunit
```

## Ejecutar un test específico

```bash
./vendor/bin/phpunit --filter MathHelperTest
./vendor/bin/phpunit --filter CalculatorTest
./vendor/bin/phpunit --filter PasswordValidatorTest
./vendor/bin/phpunit --filter UserServiceTest
```

## Estructura

```
src/
├── MathHelper.php              # Paso 1 — Funciones puras
├── Calculator.php              # Paso 2 — Clase con estado
├── PasswordValidator.php       # Paso 3 — Para DataProvider
├── UserRepositoryInterface.php # Paso 4 — Interfaz para Mock
└── UserService.php             # Paso 4 — Servicio con dependencia

tests/Unit/
├── MathHelperTest.php          # Paso 1 — assertEquals, expectException
├── CalculatorTest.php          # Paso 2 — setUp(), assertCount, assertEmpty
├── PasswordValidatorTest.php   # Paso 3 — @dataProvider
└── UserServiceTest.php         # Paso 4 — createMock, expects, willReturn
```
