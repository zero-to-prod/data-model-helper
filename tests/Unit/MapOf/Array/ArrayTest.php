<?php

namespace Tests\Unit\MapOf\Array;

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

    #[Test] public function nested(): void
    {
        $User = User::from([
            'AliasesNested' => [
                [
                    ['name' => 'John Doe'],
                    ['name' => 'John Smith'],
                ],
                [
                    ['name' => 'John Doe'],
                    ['name' => 'John Smith'],
                ]
            ]
        ]);

        self::assertEquals('John Doe', $User->AliasesNested[0][0]->name);
        self::assertEquals('John Smith', $User->AliasesNested[0][1]->name);

        self::assertEquals('John Doe', $User->AliasesNested[1][0]->name);
        self::assertEquals('John Smith', $User->AliasesNested[1][1]->name);
    }
}