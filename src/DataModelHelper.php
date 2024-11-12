<?php

namespace Zerotoprod\DataModelHelper;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\PropertyRequiredException;
use Zerotoprod\ValidateUrl\ValidateUrl;

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
        if (!$value && $Property->getType()?->allowsNull()) {
            return null;
        }

        $args = $Attribute?->getArguments()[0];
        $value = isset($args['coerce']) && !isset($value[0]) ? [$value] : $value;

        if (isset($args['using'])) {
            return ($args['using'])($value);
        }

        $method = $args['method'] ?? 'from';
        $type = $Property->getType()?->getName();
        $map = $args['map_via'] ?? 'map';

        $mapper = static function ($value, $level = 1) use ($args, $map, $type, $method, &$mapper) {
            return $type === 'array'
                ? array_map(static fn($item) => $level <= 1
                    ? $args['type']::$method($item)
                    : $mapper($item, $level - 1),
                    ($args['key_by'] ?? null) && count(array_column($value, ($args['key_by'] ?? null)))
                        ? array_combine(array_column($value, ($args['key_by'] ?? null)), $value)
                        : $value)
                : (new $type(
                    is_callable($args['map'] ?? null)
                        ? $args['map']($value)
                        : $value
                ))
                    ->$map(
                        fn($item) => $level <= 1
                            ? $args['type']::$method($item)
                            : $mapper($item, $level - 1)
                    );
        };

        return $mapper($value, $args['level'] ?? 1);
    }

    /**
     * Perform a regular expression search and replace.
     *
     * NOTE: If property allows null, null will be returned, else an empty string.
     *
     * ```
     *  #[Describe([
     *      'cast' => [self::class, 'pregReplace'],
     *      'pattern' => '/s/', // any regular expression
     *      'replacement' => '' // default
     *  ])]
     * ```
     */
    public static function pregReplace(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): array|string|null
    {
        if (!$value) {
            return $Property->getType()?->allowsNull()
                ? null
                : '';
        }
        $args = $Attribute?->getArguments()[0];

        return preg_replace($args['pattern'], $args['replacement'] ?? '', $value);
    }

    /**
     * Determine if a given value is a valid URL.
     *  ```
     *   #[Describe([
     *       'cast' => [self::class, 'isUrl'],
     *       'protocols' => ['http', 'udp'], // Optional. Defaults to all.
     *       'on_fail' => [MyAction::class, 'method'], // Optional. Invoked when validation fails.
     *       'exception' => InvalidUrlException::class, // Optional. Throws an exception when not url.
     *   ])]
     *  ```
     */
    public static function isUrl(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): ?string
    {
        $args = $Attribute?->getArguments()[0];
        if (!$value && $Property->getType()?->allowsNull()) {
            return null;
        }

        if (!$value || in_array('required', $args, true)) {
            throw new PropertyRequiredException("Property `\${$Property->getName()}` is required");
        }

        if (!is_string($value)) {
            if (isset($args['on_fail'])) {
                call_user_func($args['on_fail'], $value, $context, $Attribute, $Property);
            }

            if (isset($args['exception'])) {
                throw new $args['exception'];
            }
        }

        if (!ValidateUrl::isUrl($value, $args['protocols'] ?? [])) {
            if (isset($args['on_fail'])) {
                call_user_func($args['on_fail'], $value, $context, $Attribute, $Property);
            }
            if (isset($args['exception'])) {
                throw new $args['exception'];
            }
        }

        return $value;
    }
}