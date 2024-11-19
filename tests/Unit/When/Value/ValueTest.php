<?php

namespace Tests\Unit\When\Value;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ValueTest extends TestCase
{
    #[Test] public function value(): void
    {
        $User = User::from([
            User::value => 100,
        ]);

        self::assertEquals(100, $User->value);
    }
}