<?php

namespace Tests\Unit\Array;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ArrayTest extends TestCase
{
    #[Test] public function from(): void
    {
        $User = User::from([
            'Aliases' => [
                ['name' => 'John Doe'],
                ['name' => 'John Smith'],
            ]
        ]);

        self::assertEquals('John Doe', $User->Aliases[0]->name);
        self::assertEquals('John Smith', $User->Aliases[1]->name);
    }
}