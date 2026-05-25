<?php

interface IInventoryRepository
{
    public function getStock(string $productId): int;

    public function decreaseStock(string $productId, int $quantity): void;
}

interface INotificationService
{
    public function sendConfirmation(string $userId, string $orderId): void;
}