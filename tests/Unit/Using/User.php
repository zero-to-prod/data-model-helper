<?php

namespace Tests\Unit\Using;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

readonly class User
{
    use DataModel;
    use DataModelHelper;

    /** @var Collection $Aliases */
    #[Describe([
        'cast' => [DataModelHelper::class, 'mapOf'],
        'type' => Alias::class,
        'using' => [User::class, 'map']
    ])]
    public Collection $Aliases;

    public static function map(array $values): Collection
    {
        $Collection = new Collection();
        $Collection->items = $values;

        return $Collection;
    }
}