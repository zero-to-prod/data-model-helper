<?php

namespace Tests\Unit\IsEmail\Required;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class RequiredTest extends TestCase
{
    #[Test] public function required(): void
    {
        $this->expectException(PropertyRequiredException::class);
        $this->expectExceptionMessage('Property `$email` is required.');
        User::from();
    }

    #[Test] public function required_alt(): void
    {
        $this->expectException(PropertyRequiredException::class);
        User::from([
            User::email => 'john@example.com'
        ]);
    }
}