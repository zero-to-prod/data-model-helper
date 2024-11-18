<?php

namespace Tests\Unit\PregReplace;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class PregReplaceRequiredTest extends TestCase
{
    #[Test] public function pattern(): void
    {
        $this->expectException(PropertyRequiredException::class);

        UserRequired::from();
    }
}