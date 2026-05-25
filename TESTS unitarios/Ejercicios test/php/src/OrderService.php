<?php

require_once 'Contracts.php';
require_once 'InsufficientStockError.php';

class OrderService
{
    private IInventoryRepository $inventoryRepository;
    private INotificationService $notificationService;

    public function __construct(
        IInventoryRepository $inventoryRepository,
        INotificationService $notificationService
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->notificationService = $notificationService;
    }

    public function placeOrder(string $userId, string $productId, int $quantity): array
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Cantidad inválida");
        }

        $stock = $this->inventoryRepository->getStock($productId);

        if ($stock < $quantity) {
            throw new InsufficientStockError("Stock insuficiente");
        }

        $this->inventoryRepository->decreaseStock($productId, $quantity);

        $order = [
            "orderId" => uniqid(),
            "userId" => $userId,
            "productId" => $productId,
            "quantity" => $quantity,
            "status" => "confirmed"
        ];

        $this->notificationService->sendConfirmation(
            $userId,
            $order["orderId"]
        );

        return $order;
    }
}