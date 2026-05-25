<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Contracts.php';
require_once __DIR__ . '/../src/OrderService.php';

class OrderServiceTest extends TestCase
{
    public function testPlaceOrderValidOrder()
    {
        $repoMock = $this->createMock(IInventoryRepository::class);

        $notificationMock = $this->createMock(INotificationService::class);

        $repoMock->method('getStock')->willReturn(10);

        $repoMock->expects($this->once())
            ->method('decreaseStock');

        $notificationMock->expects($this->once())
            ->method('sendConfirmation');

        $service = new OrderService(
            $repoMock,
            $notificationMock
        );

        $result = $service->placeOrder("u1", "p1", 2);

        $this->assertEquals("confirmed", $result["status"]);
    }

    public function testPlaceOrderInsufficientStock()
    {
        $repoMock = $this->createMock(IInventoryRepository::class);

        $notificationMock = $this->createMock(INotificationService::class);

        $repoMock->method('getStock')->willReturn(1);

        $this->expectException(InsufficientStockError::class);

        $service = new OrderService(
            $repoMock,
            $notificationMock
        );

        $service->placeOrder("u1", "p1", 5);
    }
}