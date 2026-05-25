const StringHelper = require("./StringHelper");

describe("StringHelper", () => {

    test("truncate works", () => {
        expect(
            StringHelper.truncate("Hola Mundo", 4)
        ).toBe("Hola...");
    });

    test("toSlug works", () => {
        expect(
            StringHelper.toSlug("¡Hola Mundo! 2024")
        ).toBe("hola-mundo-2024");
    });

    test("countWords works", () => {
        expect(
            StringHelper.countWords("Hola mundo desde Node")
        ).toBe(4);
    });
});