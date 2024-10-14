# `Zerotoprod\DataModelHelper`

[![Repo](https://img.shields.io/badge/github-gray?logo=github)](https://github.com/zero-to-prod/data-model-helper)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/zero-to-prod/data-model-helper.svg)](https://packagist.org/packages/zero-to-prod/data-model-helper)
![test](https://github.com/zero-to-prod/data-model-helper/actions/workflows/phpunit.yml/badge.svg)
![Downloads](https://img.shields.io/packagist/dt/zero-to-prod/data-model-helper.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/zero-to-prod/data-model-helper&#41)

Utilities for casting values using the [DataModel](https://github.com/zero-to-prod/data-model) package.

## Installation

Install the package via Composer:

```bash
composer require zero-to-prod/data-model-helper
```

## Quick Start

Here’s how to use the `mapOf` helper with all its arguments:

```php
readonly class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Collection $Aliases */
    #[Describe([
        'cast'    => [DataModelHelper::class, 'mapOf'], // Casting method to use
        'type'    => Alias::class,                      // Target type for each item
        'coerce'  => true,                              // Coerce single elements into an array
        'using'   => [User::class, 'map'],              // Custom mapping function
        'map_via' => 'mapper',                          // Custom mapping method (defaults to 'map')
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
readonly class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Alias[] $Aliases */
    #[Describe([
        'cast' => [DataModelHelper::class, 'mapOf'], // Use the mapOf helper method
        'type' => Alias::class,                      // Target type for each item
    ])]
    public array $Aliases;
}

readonly class Alias
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
readonly class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Collection<int, Alias> $Aliases */
    #[Describe([
        'cast' => [DataModelHelper::class, 'mapOf'],
        'type' => Alias::class,
    ])]
    public \Illuminate\Support\Collection $Aliases;
}

readonly class Alias
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

Sometimes, an attribute may contain either a single element or an array of elements. By setting `'coerce' => true`, you can ensure that single elements
are coerced into an array.

```php
readonly class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Alias[] $Aliases */
    #[Describe([
        'cast'   => [DataModelHelper::class, 'mapOf'],
        'type'   => Alias::class,
        'coerce' => true, // Coerce single elements into an array
    ])]
    public array $Aliases;
}

readonly class Alias
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
readonly class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Collection $Aliases */
    #[Describe([
        'cast'  => [DataModelHelper::class, 'mapOf'],
        'type'  => Alias::class,
        'using' => [User::class, 'map'], // Use custom mapping function
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

readonly class Alias
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
readonly class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModelHelper\DataModelHelper;
    
    /** @var Collection $Aliases */
    #[Describe([
        'cast'    => [DataModelHelper::class, 'mapOf'],
        'type'    => Alias::class,
        'map_via' => 'mapper', // Use custom mapping method for the `Collection` class.
    ])]
    public Collection $Aliases;
}

readonly class Alias
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
