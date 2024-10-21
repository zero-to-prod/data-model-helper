<?php

namespace Tests\Unit\MapVia;

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
        'map_via' => 'mapper',
    ])]
    public Collection $Aliases;

    /** @var Collection<Collection> $AliasesNested */
    #[Describe([
        'cast' => [self::class, 'mapOf'],
        'type' => Alias::class,
        'map_via' => 'mapper',
        'level' => 2,
    ])]
    public Collection $AliasesNested;
}