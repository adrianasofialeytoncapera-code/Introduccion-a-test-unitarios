<?php

namespace MyApp;

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $price,
        public int $stock
    ) {}
}

class ProductService
{
    /** @var Product[] */
    private array $products = [];

    public function addProduct(Product $product): void
    {
        if ($product->price <= 0) {
            throw new \InvalidArgumentException("El precio debe ser positivo.");
        }
        foreach ($this->products as $p) {
            if ($p->id === $product->id) {
                throw new \InvalidArgumentException("Ya existe un producto con Id {$product->id}.");
            }
        }
        $this->products[] = $product;
    }

    /** @return Product[] */
    public function getAffordable(float $maxPrice): array
    {
        $filtered = array_filter($this->products, fn($p) => $p->price <= $maxPrice);
        usort($filtered, fn($a, $b) => $a->price <=> $b->price);
        return array_values($filtered);
    }

    public function tryReduceStock(int $productId, int $quantity, string &$message): bool
    {
        foreach ($this->products as $product) {
            if ($product->id === $productId) {
                if ($product->stock < $quantity) {
                    $message = "Stock insuficiente.";
                    return false;
                }
                $product->stock -= $quantity;
                $message = "OK";
                return true;
            }
        }
        $message = "Producto no encontrado.";
        return false;
    }
}
