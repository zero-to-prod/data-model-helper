<?php

namespace Tests\Unit\PregReplace;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PregReplaceTest extends TestCase
{
    #[Test] public function pattern(): void
    {
        $User = User::from([
            User::name => 'Trophy🏆',
            User::lastname => 'stop',
        ]);

        self::assertEquals('Trophy', $User->name);
        self::assertEquals('1top', $User->lastname);
    }

    #[Test] public function missing_null_value(): void
    {
        $User = User::from([
            User::lastname => 'stop',
        ]);

        self::assertNull($User->name);
        self::assertEquals('1top', $User->lastname);
    }

    #[Test] public function missing_empty_value(): void
    {
        $User = User::from([
            User::name => 'Trophy🏆',
        ]);

        self::assertEquals('Trophy', $User->name);
        self::assertEquals('', $User->lastname);
    }
}