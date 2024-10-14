<?php

namespace Tests\Unit\MapVia;

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
        'map_via' => 'mapper'
    ])]
    public Collection $Aliases;
}