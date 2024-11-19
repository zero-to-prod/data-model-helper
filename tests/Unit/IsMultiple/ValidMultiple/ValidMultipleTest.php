<?php

namespace Tests\Unit\IsMultiple\ValidMultiple;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ValidMultipleTest extends TestCase
{
    #[Test] public function valid(): void
    {
        $User = User::from([
            User::value => 100,
        ]);

        self::assertEquals(100, $User->value);
    }
}