class InsufficientStockError extends Error {}

class OrderService {

    constructor(inventoryRepository, notificationService) {
        this.inventoryRepository = inventoryRepository;
        this.notificationService = notificationService;
    }

    placeOrder(userId, productId, quantity) {

        if (quantity <= 0) {
            throw new Error("Cantidad inválida");
        }

        const stock = this.inventoryRepository.getStock(productId);

        if (stock < quantity) {
            throw new InsufficientStockError("Stock insuficiente");
        }

        this.inventoryRepository.decreaseStock(productId, quantity);

        const order = {
            orderId: Date.now().toString(),
            userId,
            productId,
            quantity,
            status: "confirmed"
        };

        this.notificationService.sendConfirmation(
            userId,
            order.orderId
        );

        return order;
    }
}

module.exports = {
    OrderService,
    InsufficientStockError
};