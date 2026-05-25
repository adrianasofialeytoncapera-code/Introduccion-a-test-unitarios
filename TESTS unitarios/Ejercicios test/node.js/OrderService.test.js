const {
    OrderService,
    InsufficientStockError
} = require("./OrderService");

describe("OrderService", () => {

    test("valid order", () => {

        const mockRepo = {
            getStock: jest.fn().mockReturnValue(10),
            decreaseStock: jest.fn()
        };

        const mockNotification = {
            sendConfirmation: jest.fn()
        };

        const service = new OrderService(
            mockRepo,
            mockNotification
        );

        const result = service.placeOrder("u1", "p1", 2);

        expect(result.status).toBe("confirmed");

        expect(mockRepo.decreaseStock)
            .toHaveBeenCalled();

        expect(mockNotification.sendConfirmation)
            .toHaveBeenCalled();
    });

    test("insufficient stock", () => {

        const mockRepo = {
            getStock: jest.fn().mockReturnValue(1),
            decreaseStock: jest.fn()
        };

        const mockNotification = {
            sendConfirmation: jest.fn()
        };

        const service = new OrderService(
            mockRepo,
            mockNotification
        );

        expect(() => {
            service.placeOrder("u1", "p1", 5);
        }).toThrow(InsufficientStockError);
    });
});