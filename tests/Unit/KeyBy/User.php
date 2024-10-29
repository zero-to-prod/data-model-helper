<?php

namespace Tests\Unit\KeyBy;

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
        'key_by' => 'id'
    ])]
    public array $Aliases;

    /** @var Alias[][] $AliasesNested */
    #[Describe([
        'cast' => [self::class, 'mapOf'],
        'type' => Alias::class,
        'level' => 2,
        'key_by' => 'id'
    ])]
    public array $AliasesNested;
}