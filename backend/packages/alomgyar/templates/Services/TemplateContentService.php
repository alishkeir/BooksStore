<?php

namespace Alomgyar\Templates\Services;

use Alomgyar\Templates\Templates;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\Pure;

class TemplateContentService
{
    #[Pure]
    public static function create(): self
    {
        return new self();
    }

       public function getTemplateContent(string $slug, int $storeId = 0, $disabledCache = false)
       {
           /** @todo kapcsoljuk vissza ha minden rendben van már a templatekkel! */
           $disabledCache = true;
           if ($disabledCache) {
               return Templates::where([
                   'slug' => $slug,
                   'store' => $storeId,
               ])->firstOrFail();
           } else {
               return Cache::rememberForever("templates_content_{$slug}_{$storeId}", function () use ($slug, $storeId) {
                   return Templates::where([
                       'slug' => $slug,
                       'store' => $storeId,
                   ])->firstOrFail();
               });
           }
       }
}
