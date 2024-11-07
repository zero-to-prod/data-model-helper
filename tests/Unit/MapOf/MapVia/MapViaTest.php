<?php

namespace Tests\Unit\MapOf\MapVia;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MapViaTest extends TestCase
{
    #[Test] public function from(): void
    {
        $User = User::from([
            'Aliases' => [['name' => 'John Doe']],
        ]);

        self::assertEquals('John Doe', $User->Aliases->items[0]->name);
    }

    #[Test] public function nested(): void
    {
        $User = User::from([
            'Aliases' => [['name' => 'John Doe']],
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

        self::assertEquals('John Doe', $User->AliasesNested->items[0]->items[0]->name);
        self::assertEquals('John Smith', $User->AliasesNested->items[0]->items[1]->name);

        self::assertEquals('John Doe', $User->AliasesNested->items[1]->items[0]->name);
        self::assertEquals('John Smith', $User->AliasesNested->items[1]->items[1]->name);
    }
}