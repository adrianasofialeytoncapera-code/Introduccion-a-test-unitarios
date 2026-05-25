<?php

use MyApp\Product;
use MyApp\ProductService;
use MyTests\TestRunner;

return function (TestRunner $t): void {

    $t->suite('ProductServiceTests', function (TestRunner $t): void {

        // Helper para poblar el servicio con productos de prueba
        $seed = function (ProductService $service): void {
            $service->addProduct(new Product(1, 'Laptop',  899.99, 10));
            $service->addProduct(new Product(2, 'Mouse',    19.99, 50));
            $service->addProduct(new Product(3, 'Monitor', 299.99,  5));
        };

        $t->test('AddProduct_InvalidPrice_ThrowsInvalidArgumentException', function (TestRunner $t): void {
            $service = new ProductService();

            $ex = $t->assertThrows(
                \InvalidArgumentException::class,
                fn() => $service->addProduct(new Product(1, 'Teclado', -10, 5))
            );
            $t->assertContains('positivo', $ex->getMessage());
        });

        $t->test('GetAffordable_ReturnsFilteredAndOrdered', function (TestRunner $t) use ($seed): void {
            $service = new ProductService();
            $seed($service);

            $affordable = $service->getAffordable(300.0);

            // Log de diagnóstico (equivalente a ITestOutputHelper)
            echo "    [log] Asequibles encontrados: " . count($affordable) . "\n";

            $t->assertCount(2, $affordable);                                 // Mouse y Monitor
            $t->assertEquals('Mouse', $affordable[0]->name);                // ordenado por precio
            $t->assertAll($affordable, fn($p) => $t->assertTrue($p->price <= 300.0));
        });

        $t->test('TryReduceStock_SufficientStock_ReturnsTrueAndReduces', function (TestRunner $t) use ($seed): void {
            $service = new ProductService();
            $seed($service);

            $message = '';
            $success = $service->tryReduceStock(2, 3, $message);

            $t->assertTrue($success);
            $t->assertEquals('OK', $message);
        });

        $t->test('TryReduceStock_InsufficientStock_ReturnsFalse', function (TestRunner $t) use ($seed): void {
            $service = new ProductService();
            $seed($service);

            $message = '';
            $success = $service->tryReduceStock(3, 100, $message);

            $t->assertFalse($success);
            $t->assertEquals('Stock insuficiente.', $message);
        });

    });

};
