<?php

namespace Tests\Unit\Coerced;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

readonly class User
{
    use DataModel;
    use DataModelHelper;

    /** @var Alias[] $Aliases */
    #[Describe([
        'cast' => [DataModelHelper::class, 'mapOf'],
        'type' => Alias::class,
        'coerce' => true
    ])]
    public array $Aliases;
}