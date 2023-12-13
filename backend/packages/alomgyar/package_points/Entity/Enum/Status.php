<?php

namespace Alomgyar\PackagePoints\Entity\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self waiting()
 * @method static self shipping()
 * @method static self arrived()
 * @method static self completed()
 * @method static self canceled()
 */
final class Status extends Enum
{
    protected static function labels(): array
    {
        return [
            'waiting' => 'Várakozó',
            'shipping' => 'Szállítás alatt',
            'arrived' => 'Átvehető',
            'completed' => 'Átvéve',
            'canceled' => 'Visszamondva',
        ];
    }
}
