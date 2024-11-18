<?php

namespace Tests\Unit\MapOf\Required;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class RequireFalseTest extends TestCase
{
    #[Test] public function from(): void
    {
        $UserFalse = UserFalse::from([
            'Aliases' => [[
                'id' => 'id'
            ]]
        ]);

        self::assertEquals('id', $UserFalse->Aliases[0]->id);
    }
}