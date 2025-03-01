<?php

namespace Tests\Unit\MapOf\Array;

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
    ])]
    public ?array $Aliases;

    /** @var Alias[][] $AliasesNested */
    #[Describe([
        'cast' => [self::class, 'mapOf'],
        'type' => Alias::class,
        'level' => 2,
    ])]
    public ?array $AliasesNested;

    /** @var Name[] $Names */
    #[Describe([
        'cast' => [self::class, 'mapOf'],
        'type' => Name::class,
    ])]
    public ?array $Names;
}