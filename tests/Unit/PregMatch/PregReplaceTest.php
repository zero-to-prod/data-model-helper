<?php

namespace Tests\Unit\PregMatch;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PregReplaceTest extends TestCase
{
    #[Test] public function pattern(): void
    {
        $User = User::from([
            User::name => 'stop',
            User::s => 'stop',
            User::as_null => 'top',
            User::offset => 'stop',
        ]);

        self::assertEquals(['s'], $User->name);
        self::assertEquals('s', $User->s);
        self::assertNull($User->as_null);
        self::assertEquals([], $User->offset);
    }
}