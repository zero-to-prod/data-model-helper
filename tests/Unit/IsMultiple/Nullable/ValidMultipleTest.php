<?php

namespace Tests\Unit\IsMultiple\Nullable;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ValidMultipleTest extends TestCase
{
    #[Test] public function valid(): void
    {
        $User = User::from([
            User::value => null,
        ]);

        self::assertNull($User->value);
    }
}