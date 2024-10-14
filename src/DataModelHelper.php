<?php

namespace Zerotoprod\DataModelHelper;

use ReflectionAttribute;
use ReflectionProperty;

trait DataModelHelper
{
    /**
     * Maps an array of values to instances of a specified type.
     *
     * ```
     * readonly class User
     * {
     *  use \Zerotoprod\DataModel\DataModel;
     *  use \Zerotoprod\DataModelHelper\DataModelHelper;
     *
     *  #[Describe([
     *      'cast'    => [DataModelHelper::class, 'mapOf'], // Casting method to use
     *      'type'    => Alias::class,                      // Target type for each item
     *      'coerce'  => true,                              // Coerce single elements into an array
     *      'using'   => [User::class, 'map'],              // Custom mapping function
     *      'map_via' => 'mapper',                          // Custom mapping method (defaults to 'map')
     *  ])]
     *  public Collection $Aliases;
     *
     *  public static function map(array $values): Collection
     *  {
     *      $Collection = new Collection();
     *      $Collection->items = $values;
     *
     *      return $Collection;
     *  }
     * }
     *
     * readonly class Alias
     * {
     *  use \Zerotoprod\DataModel\DataModel;
     *
     *  public string $name;
     * }
     * ```
     *
     * @link https://github.com/zero-to-prod/data-model-helper
     * @see  https://github.com/zero-to-prod/data-model
     */
    public static function mapOf(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property)
    {
        $args = $Attribute?->getArguments();
        $value = isset($args[0]['coerce']) ? [$value] : $value;

        if (isset($Attribute?->getArguments()[0]['using'])) {
            return ($Attribute?->getArguments()[0]['using'])($value);
        }

        $method = $Attribute?->getArguments()[0]['method'] ?? 'from';
        $type = $Property->getType()?->getName();
        $map = $Attribute?->getArguments()[0]['map_via'] ?? 'map';

        return $type === 'array'
            ? array_map(static fn($item) => $args[0]['type']::$method($item), $value)
            : (new $type($value))->$map(fn($item) => $args[0]['type']::$method($item));
    }
}