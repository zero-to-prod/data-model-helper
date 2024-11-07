<?php

namespace Zerotoprod\DataModelHelper;

use ReflectionAttribute;
use ReflectionProperty;

/**
 * Provides helper methods for casting and mapping values for classes using the DataModel package.
 *
 * @link    https://github.com/zero-to-prod/data-model-helper
 *
 * @see     https://github.com/zero-to-prod/data-model
 * @see     https://github.com/zero-to-prod/data-model-factory
 * @see     https://github.com/zero-to-prod/transformable
 *
 * @package Zerotoprod\DataModelHelper
 */
trait DataModelHelper
{
    /**
     * Maps an array of values to instances of a specified type.
     *
     * ```
     * class User
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
     *      'level'   => 1,                                 // The dimension of the array. Defaults to 1.
     *      'key_by' => 'key',                              // Key an associative array by a field.
     *  ])]
     *  public Collection $Aliases;
     * }
     * ```
     *
     * @link    https://github.com/zero-to-prod/data-model-helper
     *
     * @see     https://github.com/zero-to-prod/data-model
     * @see     https://github.com/zero-to-prod/data-model-factory
     * @see     https://github.com/zero-to-prod/transformable
     *
     * @package Zerotoprod\DataModelHelper
     */
    public static function mapOf(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property)
    {
        $args = $Attribute?->getArguments();
        $value = isset($args[0]['coerce']) && !isset($value[0]) ? [$value] : $value;

        if (isset($args[0]['using'])) {
            return ($args[0]['using'])($value);
        }

        $method = $args[0]['method'] ?? 'from';
        $type = $Property->getType()?->getName();
        $map = $args[0]['map_via'] ?? 'map';

        $mapper = static function ($value, $level = 1) use ($args, $map, $type, $method, &$mapper) {
            return $type === 'array'
                ? array_map(static fn($item) => $level <= 1
                    ? $args[0]['type']::$method($item)
                    : $mapper($item, $level - 1),
                    ($args[0]['key_by'] ?? null) && count(array_column($value, ($args[0]['key_by'] ?? null)))
                        ? array_combine(array_column($value, ($args[0]['key_by'] ?? null)), $value)
                        : $value)
                : (new $type(
                    is_callable($args[0]['map'] ?? null)
                        ? $args[0]['map']($value)
                        : $value
                ))
                    ->$map(
                        fn($item) => $level <= 1
                            ? $args[0]['type']::$method($item)
                            : $mapper($item, $level - 1)
                    );
        };

        return $mapper($value, $args[0]['level'] ?? 1);
    }

    public static function pregReplace(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): array|string|null
    {
        if (!$value) {
            return $Property->getType()?->allowsNull()
                ? null
                : '';
        }
        $args = $Attribute?->getArguments();

        return preg_replace($args[0]['pattern'], $args[0]['replacement'] ?? '', $value);
    }
}