<?php

namespace Tests\Unit\IsMultiple\Exception\InvalidUrl;

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
        'of' => 3,
        'exception' => NotMultipleException::class
    ])]
    public int $value;
}