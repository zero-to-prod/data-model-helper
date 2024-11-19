<?php

namespace Tests\Unit\When\Nullable;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NullTest extends TestCase
{
    #[Test] public function valid(): void
    {
        $User = User::from([
            User::value => null,
        ]);

        self::assertNull($User->value);
    }
}