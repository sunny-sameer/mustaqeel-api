<?php


namespace App\Services;

class MailMergeService
{
    public function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }

    public function extractPlaceholders(string $content): array
    {
        preg_match_all('/\{\{(\w+)\}\}/', $content, $matches);
        return $matches[1] ?? [];
    }
}
