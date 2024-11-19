<?php

namespace Tests\Unit\IsMultiple\Required;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class RequiredTest extends TestCase
{
    #[Test] public function required(): void
    {
        $this->expectException(PropertyRequiredException::class);
        $this->expectExceptionMessage('Property `$value` is required.');
        User::from();
    }
}