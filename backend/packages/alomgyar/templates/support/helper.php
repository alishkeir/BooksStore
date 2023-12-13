<?php

use Alomgyar\Templates\Services\TemplateContentService;

if (! function_exists('template')) {
    function template(string $slug, mixed $safeBack = null, bool $cache = true)
    {
        return TemplateContentService::create()->getTemplateContent($slug, $safeBack ?? false, $cache);
    }
}
