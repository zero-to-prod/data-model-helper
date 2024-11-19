<?php

namespace Tests\Unit\IsMultiple\ValidMultiple;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const value = 'value';

    #[Describe([
        'cast' => [self::class, 'isMultiple'],
        'of' => 10
    ])]
    public int $value;
}