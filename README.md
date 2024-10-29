# Zerotoprod\DataModelHelper

![](./logo.png)

[![Repo](https://img.shields.io/badge/github-gray?logo=github)](https://github.com/zero-to-prod/data-model-helper)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/zero-to-prod/data-model-helper/test.yml?label=tests)](https://github.com/zero-to-prod/data-model-helper/actions)
[![Packagist Downloads](https://img.shields.io/packagist/dt/zero-to-prod/data-model-helper?color=blue)](https://packagist.org/packages/zero-to-prod/data-model-helper/stats)
[![php](https://img.shields.io/packagist/php-v/zero-to-prod/data-model-helper.svg?color=purple)](https://packagist.org/packages/zero-to-prod/data-model-helper/stats)
[![Packagist Version](https://img.shields.io/packagist/v/zero-to-prod/data-model-helper?color=f28d1a)](https://packagist.org/packages/zero-to-prod/data-model-helper)
[![License](https://img.shields.io/packagist/l/zero-to-prod/data-model-helper?color=pink)](https://github.com/zero-to-prod/data-model-helper/blob/main/LICENSE.md)

Utilities for casting values using the [DataModel](https://github.com/zero-to-prod/data-model) package.

## Installation

Install the package via Composer:

```bash
composer require zero-to-prod/data-model-helper
```

### Additional Packages

- [DataModel](https://github.com/zero-to-prod/data-model): Transform data into a class.
- [DataModelFactory](https://github.com/zero-to-prod/data-model-factory): A factory helper to set the value of your `DataModel`.
- [Transformable](https://github.com/zero-to-prod/transformable): Transform a `DataModel` into different types.

## Quick Start

Here’s how to use the `mapOf` helper with all its arguments:

```php
use Zerotoprod\DataModel\Describe;

class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Collection<int, Alias> $Aliases */
    #[Describe([
        'cast'    => [self::class, 'mapOf'],    // Casting method to use
        'type'    => Alias::class,              // Target type for each item
        'coerce'  => true,                      // Coerce single elements into an array
        'using'   => [self::class, 'map'],      // Custom mapping function
        'map_via' => 'mapper',                  // Custom mapping method (defaults to 'map')
        'map' => [self::class, 'keyBy'],        // Run a function for that value.
        'level' => 1,                           // The dimension of the array. Defaults to 1.
        'key_by' => 'key',                      // Key an associative array by a field.
    ])]
    public Collection $Aliases;
}
```

## Usage

### Including the Trait

Include the `DataModelHelper` trait in your class to access helper methods:

```php
class DataModelHelper
{
    use \Zerotoprod\DataModelHelper\DataModelHelper;
}
```

### mapOf()

The `mapOf()` method returns an array of `Alias` instances.

```php
use Zerotoprod\DataModel\Describe;

class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Alias[] $Aliases */
    #[Describe([
        'cast' => [self::class, 'mapOf'],   // Use the mapOf helper method
        'type' => Alias::class,             // Target type for each item
    ])]
    public array $Aliases;
}

class Alias
{
    use \Zerotoprod\DataModel\DataModel;
    
    public string $name;
}

$User = User::from([
    'Aliases' => [
        ['name' => 'John Doe'],
        ['name' => 'John Smith'],
    ]
]);

echo $User->Aliases[0]->name; // Outputs: John Doe
echo $User->Aliases[1]->name; // Outputs: John Smith
```

#### Laravel Collection Example

The `mapOf` helper is designed to work will with the `\Illuminate\Support\Collection` class.

```php
use Zerotoprod\DataModel\Describe;

class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Collection<int, Alias> $Aliases */
    #[Describe([
        'cast' => [self::class, 'mapOf'],
        'type' => Alias::class,
    ])]
    public \Illuminate\Support\Collection $Aliases;
}

class Alias
{
    use \Zerotoprod\DataModel\DataModel;
    
    public string $name;
}

$User = User::from([
    'Aliases' => [
        ['name' => 'John Doe'],
        ['name' => 'John Smith'],
    ]
]);

echo $User->Aliases->first()->name; // Outputs: John Doe
```

#### Coercing

Sometimes, an attribute may contain either a single element or an array of elements. By setting `'coerce' => true`, you can ensure that single
elements
are coerced into an array.

```php
use Zerotoprod\DataModel\Describe;

class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Alias[] $Aliases */
    #[Describe([
        'cast'   => [self::class, 'mapOf'],
        'type'   => Alias::class,
        'coerce' => true, // Coerce single elements into an array
    ])]
    public array $Aliases;
}

class Alias
{
    use \Zerotoprod\DataModel\DataModel;
    
    public string $name;
}

$User = User::from([
    'Aliases' => ['name' => 'John Doe'], // Single element instead of an array
]);

echo $User->Aliases[0]->name; // Outputs: John Doe
```

#### Using a Custom Mapping Function

Specify your mapping function by setting the `using` option.

```php
use Zerotoprod\DataModel\Describe;

class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Collection $Aliases */
    #[Describe([
        'cast'  => [self::class, 'mapOf'],
        'type'  => Alias::class,
        'using' => [self::class, 'map'], // Use custom mapping function
    ])]
    public Collection $Aliases;

    public static function map(array $values): Collection
    {
        // Map each value to an Alias instance
        $items = array_map(fn($value) => Alias::from($value), $values);

        // Return as a Collection
        return new Collection($items);
    }
}

class Alias
{
    use \Zerotoprod\DataModel\DataModel;
    
    public string $name;
}

class Collection
{
    public array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }
}

$User = User::from([
    'Aliases' => [
        ['name' => 'John Doe'],
    ],
]);

echo $User->Aliases->items[0]->name; // Outputs: John Doe
```

#### Specifying a Custom Mapping Method

By default, the map method is used to map over elements. You can specify a different method using the `map_via` option.

```php
use Zerotoprod\DataModel\Describe;

class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Collection $Aliases */
    #[Describe([
        'cast'    => [self::class, 'mapOf'],
        'type'    => Alias::class,
        'map_via' => 'mapper', // Use custom mapping method for the `Collection` class.
    ])]
    public Collection $Aliases;
}

class Alias
{
    use \Zerotoprod\DataModel\DataModel;

    public string $name;
}

class Collection
{
    public array $items;

    public function __construct(array $values)
    {
        $this->items = $values;
    }

    public function mapper(callable $callable): Collection
    {
        $this->items = array_map($callable, $this->items);
        return $this;
    }
}

$User = User::from([
    'Aliases' => [
        ['name' => 'John Doe'],
    ],
]);

echo $User->Aliases->items[0]->name; // Outputs: John Doe
```

#### Deep Mapping

You can set the level for mapping deep arrays.

```php
use Zerotoprod\DataModel\Describe;

class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Alias[] $Aliases */
    #[Describe([
        'cast' => [self::class, 'mapOf'],   // Use the mapOf helper method
        'type' => Alias::class,             // Target type for each item
        'level' => 2,                       // The dimension of the array. Defaults to 1.
    ])]
    public array $Aliases;
}

class Alias
{
    use \Zerotoprod\DataModel\DataModel;
    
    public string $name;
}

$User = User::from([
    'Aliases' => [
        [
            ['name' => 'John Doe'],
            ['name' => 'John Smith'],
        ]
    ]
]);

echo $User->Aliases[0][0]->name; // Outputs: John Doe
echo $User->Aliases[0][1]->name; // Outputs: John Smith
```

#### KeyBy
Key an array by an element value by using the `key_by` argument.

This also supports deep mapping.

Note: this only applies to arrays.
```php
class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Alias[] $Aliases */
    #[Describe([
        'cast' => [self::class, 'mapOf'],   
        'type' => Alias::class,             
        'key_by' => 'id',
    ])]
    public array $Aliases;
}

class Alias
{
    use \Zerotoprod\DataModel\DataModel;
    
    public string $id;
    public string $name;
}

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

echo $User->Aliases['jd1']->name;  // 'John Doe'
echo $User->Aliases['js1']->name); // 'John Smith'
```

#### Map
Call a function for that value.

Note: This does not work with arrays.
```php
class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Alias[] $Aliases */
    #[Describe([
        'cast' => [self::class, 'mapOf'],   
        'type' => Alias::class,             
        'map' => [self::class, 'keyBy'],
    ])]
    public Collection $Aliases;
    
    public static function keyBy(Collection $values): Collection
    {
        return $values->keyBy('id');
    }
}

class Alias
{
    use \Zerotoprod\DataModel\DataModel;
    
    public string $id;
    public string $name;
}

$User = User::from([
    'Aliases' => [
        [
            'id' => 'jd1',
            'name' => 'John Doe',
        ]
    ]
]);

echo $User->Aliases->get('jd1')->name;  // 'John Doe'
```