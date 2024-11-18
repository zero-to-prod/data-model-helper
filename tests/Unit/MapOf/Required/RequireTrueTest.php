<?php

namespace Tests\Unit\MapOf\Required;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class RequireTrueTest extends TestCase
{
    #[Test] public function from(): void
    {
        $this->expectException(PropertyRequiredException::class);
        UserTrue::from();
    }
}