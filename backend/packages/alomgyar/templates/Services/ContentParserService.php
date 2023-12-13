<?php

namespace Alomgyar\Templates\Services;

class ContentParserService
{
    public static function parse(string $content, array $data): string
    {
        return (new self())->parseContent($content, $data);
    }

    public function parseContent(string $content, array $data): string
    {
        foreach ($data as $key => $item) {
            $card = strtoupper($key);
            $content = str_replace("%{$card}%", $item, $content);
        }

        return $content;
    }
}
