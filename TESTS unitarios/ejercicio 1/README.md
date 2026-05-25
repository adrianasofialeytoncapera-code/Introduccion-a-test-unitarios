# PHP Tests — Sin frameworks

Traducción directa de los ejemplos de xUnit (C#) a PHP puro, sin Composer ni dependencias externas.

## Estructura

```
php-tests/
├── run_tests.php          ← punto de entrada
├── src/
│   ├── MathHelper.php
│   ├── PasswordValidator.php
│   ├── UserService.php    ← incluye User e IUserRepository
│   └── ProductService.php
└── tests/
    ├── TestRunner.php          ← framework minimalista (Fact + Theory + mocks)
    ├── MathHelperTests.php
    ├── PasswordValidatorTests.php
    ├── UserServiceTests.php    ← mock manual sin librerías
    └── ProductServiceTests.php
```

## Requisitos

- PHP 8.1 o superior (usa `readonly`, `match`, `str_starts_with`, etc.)

## Ejecutar

```bash
php run_tests.php
```

## Equivalencias xUnit → PHP

| xUnit (C#)                  | PHP (este proyecto)                     |
|-----------------------------|-----------------------------------------|
| `[Fact]`                    | `$t->test('nombre', fn...)`             |
| `[Theory][InlineData(...)]` | `$t->theory('nombre', [[...]], fn...)`  |
| `Assert.Equal`              | `$t->assertEquals`                      |
| `Assert.Throws<T>`          | `$t->assertThrows(T::class, fn...)`     |
| `Assert.All`                | `$t->assertAll($items, fn...)`          |
| `Moq` / `Mock<T>`           | clase que implementa la interfaz        |
| `Mock.Setup(...)`           | método `willReturn...()` en el mock     |
| `Mock.Verify(..., Times.Once)` | campo público `$saveCallCount`       |
| `ITestOutputHelper`         | `echo` directo en el test               |
| Constructor de clase de test | nueva instancia por closure (aislado)  |
