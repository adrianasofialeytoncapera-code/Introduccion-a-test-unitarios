class StringHelper {
    static truncate(text, maxLength, suffix = "...") {

        if (maxLength <= 0) {
            throw new Error("maxLength debe ser mayor que 0");
        }

        if (text.length <= maxLength) {
            return text;
        }

        return text.substring(0, maxLength) + suffix;
    }

    static toSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/\s+/g, "-")
            .replace(/[^a-z0-9-]/g, "")
            .replace(/-+/g, "-");
    }

    static countWords(text) {

        if (!text.trim()) {
            return 0;
        }

        return text
            .trim()
            .split(/\s+/)
            .filter(Boolean).length;
    }
}

module.exports = StringHelper;