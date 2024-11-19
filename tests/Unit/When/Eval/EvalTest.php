<?php

namespace Tests\Unit\When\Eval;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EvalTest extends TestCase
{
    #[Test] public function true(): void
    {
        $User = User::from([
            User::value => 100,
        ]);

        self::assertEquals(1, $User->value);
    }

    #[Test] public function false(): void
    {
        $User = User::from([
            User::value => 2,
        ]);

        self::assertEquals(0, $User->value);
    }
}