<?php

namespace Tests\Unit\PregMatch;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class UserRequiredTest extends TestCase
{
    #[Test] public function pattern(): void
    {
        $this->expectException(PropertyRequiredException::class);

        UserRequired::from();
    }
}