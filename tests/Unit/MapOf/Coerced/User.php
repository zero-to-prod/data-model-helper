<?php

namespace Tests\Unit\MapOf\Coerced;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    /** @var Alias[] $Aliases */
    #[Describe([
        'cast' => [self::class, 'mapOf'],
        'type' => Alias::class,
        'coerce' => true
    ])]
    public array $Aliases;
}