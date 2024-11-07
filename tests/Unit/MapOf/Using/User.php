<?php

namespace Tests\Unit\MapOf\Using;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    /** @var Collection $Aliases */
    #[Describe([
        'cast' => [self::class, 'mapOf'],
        'type' => Alias::class,
        'using' => [self::class, 'map']
    ])]
    public Collection $Aliases;

    public static function map(array $values): Collection
    {
        $Collection = new Collection();
        $Collection->items = $values;

        return $Collection;
    }
}