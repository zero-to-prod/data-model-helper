<?php

namespace Tests\Unit\IsMultiple\Required;

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
        'required'
    ])]
    public string $value;
}