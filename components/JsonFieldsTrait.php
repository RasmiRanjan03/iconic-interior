<?php

namespace app\components;

/**
 * Helpers for models that persist JSON in TEXT columns and expose
 * "one item per line" virtual attributes for admin forms.
 */
trait JsonFieldsTrait
{
    public function jsonArray(string $attribute): array
    {
        $raw = $this->getAttribute($attribute);
        if ($raw === null || $raw === '') {
            return [];
        }
        $data = json_decode((string) $raw, true);
        return is_array($data) ? array_values($data) : [];
    }

    /** Decode JSON as an associative array (object/map) */
    public function jsonAssoc(string $attribute): array
    {
        $raw = $this->getAttribute($attribute);
        if ($raw === null || $raw === '') {
            return [];
        }
        $data = json_decode((string) $raw, true);
        return is_array($data) ? $data : [];
    }

    public static function encodeJson(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /** "a\nb\n\nc" => ['a','b','c'] */
    public static function linesToArray(?string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) $text);
        return array_values(array_filter(array_map('trim', $lines), static fn($l) => $l !== ''));
    }

    /** "Key: Value" lines => ['Key' => 'Value'] */
    public static function linesToMap(?string $text): array
    {
        $map = [];
        foreach (self::linesToArray($text) as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) === 2 && trim($parts[0]) !== '') {
                $map[trim($parts[0])] = trim($parts[1]);
            }
        }
        return $map;
    }

    public static function mapToLines(array $map): string
    {
        $out = [];
        foreach ($map as $k => $v) {
            $out[] = $k . ': ' . $v;
        }
        return implode("\n", $out);
    }
}
