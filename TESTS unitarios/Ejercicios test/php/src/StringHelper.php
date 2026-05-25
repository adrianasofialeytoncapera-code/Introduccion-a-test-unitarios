<?php

class StringHelper
{
    public static function truncate(string $text, int $maxLength, string $suffix = "..."): string
    {
        if ($maxLength <= 0) {
            throw new InvalidArgumentException("maxLength debe ser mayor que 0");
        }

        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        return mb_substr($text, 0, $maxLength) . $suffix;
    }

    public static function toSlug(string $text): string
    {
        $text = mb_strtolower($text);

        // Reemplazar espacios múltiples por un guion
        $text = preg_replace('/\s+/', '-', trim($text));

        // Eliminar caracteres especiales excepto letras, números y guiones
        $text = preg_replace('/[^a-z0-9\-]/', '', $text);

        // Eliminar guiones repetidos
        $text = preg_replace('/-+/', '-', $text);

        return trim($text, '-');
    }

    public static function countWords(string $text): int
    {
        $text = trim($text);

        if ($text === '') {
            return 0;
        }

        $words = preg_split('/\s+/', $text);

        return count($words);
    }
}