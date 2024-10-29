<?php

namespace Tests\Unit\KeyBy;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class KeyByTest extends TestCase
{
    #[Test] public function from(): void
    {
        $User = User::from([
            'Aliases' => [
                [
                    'id' => 'jd1',
                    'name' => 'John Doe',
                ],
                [
                    'id' => 'js1',
                    'name' => 'John Smith'
                ],
            ]
        ]);

        self::assertEquals('John Doe', $User->Aliases['jd1']->name);
        self::assertEquals('John Smith', $User->Aliases['js1']->name);
    }

    #[Test] public function nested(): void
    {
        $User = User::from([
            'AliasesNested' => [
                [
                    [
                        'id' => 'jd1',
                        'name' => 'John Doe',
                    ],
                    [
                        'id' => 'js1',
                        'name' => 'John Smith'
                    ],
                ],
                [
                    [
                        'id' => 'jd1',
                        'name' => 'John Doe',
                    ],
                    [
                        'id' => 'js1',
                        'name' => 'John Smith'
                    ],
                ]
            ]
        ]);

        self::assertEquals('John Doe', $User->AliasesNested[0]['jd1']->name);
        self::assertEquals('John Smith', $User->AliasesNested[0]['js1']->name);

        self::assertEquals('John Doe', $User->AliasesNested[1]['jd1']->name);
        self::assertEquals('John Smith', $User->AliasesNested[1]['js1']->name);
    }
}