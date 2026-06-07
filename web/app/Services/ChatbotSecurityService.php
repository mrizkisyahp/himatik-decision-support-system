<?php

namespace App\Services;

class ChatbotSecurityService
{
    public function sanitizeText(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    public function detectPromptInjection(string $text): bool
    {
        $normalized = $this->normalizeForDetection($text);

        $unsafePhrases = [
            'ignore previous instructions',
            'ignore all instructions',
            'ignore your instructions',
            'ignore the rules',
            'forget your instructions',
            'forget previous instructions',
            'reveal system prompt',
            'show system prompt',
            'print system prompt',
            'display system prompt',
            'what is your system prompt',
            'print hidden instructions',
            'show hidden instructions',
            'jailbreak',
            'developer mode',
            'dan mode',
            'override instructions',
            'bypass instructions',
            'you are now',
            'pretend to be',
            'act as',
            'pretend you are',
            'roleplay as',
            'new instructions',
            'disregard all',
            'disregard previous',
            'do anything now',
            'no restrictions',
            'unlimited mode',
            'admin mode',
            'sudo mode',
            'god mode',
        ];

        foreach ($unsafePhrases as $phrase) {
            if (str_contains($normalized, $phrase)) {
                return true;
            }
        }

        return false;
    }

    public function detectCodeRequest(string $text): bool
    {
        $normalized = $this->normalizeForDetection($text);

        $keywords = [
            'code',
            'kode',
            'script',
            'query',
            'sql',
            'migration',
            'controller',
            'route',
            'endpoint',
            'function',
            'class',
            'model',
            'database query',
            'buat kode',
            'buat query',
            'contoh kode',
            'source code',
            'tampilkan kode',
        ];

        foreach ($keywords as $keyword) {
            if (preg_match('/\b'.preg_quote($keyword, '/').'\b/u', $normalized)) {
                return true;
            }
        }

        return false;
    }

    public function checkOutputSafety(string $text): string
    {
        if ($this->containsSecret($text)) {
            return 'Maaf, tidak bisa merespons itu.';
        }

        if ($this->containsCode($text)) {
            return 'Maaf, saya tidak dapat memberikan contoh kode program atau query teknis, namun saya dapat menjelaskan konsepnya secara teori.';
        }

        return $text;
    }

    private function normalizeForDetection(string $text): string
    {
        $text = strtolower($text);
        $text = strtr($text, [
            '0' => 'o',
            '1' => 'i',
            '3' => 'e',
            '4' => 'a',
            '5' => 's',
            '7' => 't',
            '@' => 'a',
            '$' => 's',
            '!' => 'i',
        ]);
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    private function containsSecret(string $text): bool
    {
        $patterns = [
            '/Bearer\s+[A-Za-z0-9._\-]+/i',
            '/gh[pousr]_[A-Za-z0-9_]{20,}/',
            '/(?:sk|gsk|pk)_[A-Za-z0-9_\-]{20,}/',
            '/eyJ[A-Za-z0-9_\-]+\.[A-Za-z0-9_\-]+\.[A-Za-z0-9_\-]+/',
            '/-----BEGIN [A-Z ]+PRIVATE KEY-----/',
            '/\b(?:password|secret|token|api[_-]?key)\s*[:=]\s*[^\s]+/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }

    private function containsCode(string $text): bool
    {
        $patterns = [
            '/```/',
            '/\bSELECT\b.+\bFROM\b/is',
            '/\b(?:INSERT|UPDATE|DELETE)\b.+\b(?:INTO|SET|FROM)\b/is',
            '/<\?php/i',
            '/<script\b/i',
            '/\bfunction\s+[A-Za-z_][A-Za-z0-9_]*\s*\(/',
            '/\bclass\s+[A-Za-z_][A-Za-z0-9_]*/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }
}
