<?php

namespace Tests\Unit\MapOf\Required;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class UserFalse
{
    use DataModel;
    use DataModelHelper;

    /** @var Alias[] $Aliases */
    #[Describe([
        'cast' => [self::class, 'mapOf'],
        'type' => Alias::class,
        'required' => false,
    ])]
    public ?array $Aliases;
}