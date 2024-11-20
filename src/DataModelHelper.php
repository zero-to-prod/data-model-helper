<?php

namespace Zerotoprod\DataModelHelper;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\PropertyRequiredException;
use Zerotoprod\ValidateEmail\ValidateEmail;
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
     *      'cast' => [self::class, 'mapOf'], // Casting method to use
     *      'type' => Alias::class,           // Target type for each item
     *      'required',                       // Throws PropertyRequiredException when value not present
     *      'coerce' => true,                 // Coerce single elements into an array
     *      'using' => [self::class, 'map'],  // Custom mapping function
     *      'map_via' => 'mapper',            // Custom mapping method (defaults to 'map')
     *      'map' => [self::class, 'keyBy'],  // Run a function for that value.
     *      'level' => 1,                     // The dimension of the array. Defaults to 1.
     *      'key_by' => 'key',                // Key an associative array by a field.
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
        $args = $Attribute?->getArguments()[0];
        if ((!empty($args['required']) || in_array('required', $args, true))
            && !isset($context[$Property->getName()])
        ) {
            throw new PropertyRequiredException("Property `\${$Property->getName()}` is required.");
        }

        if (!isset($context[$Property->getName()]) && $Property->getType()?->allowsNull()) {
            return null;
        }

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
     *      'pattern' => '/s/',     // any regular expression
     *      'replacement' => '',    // default
     *      'required',             // Throws PropertyRequiredException when value not present.
     *  ])]
     * ```
     */
    public static function pregReplace(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): array|string|null
    {
        $args = $Attribute?->getArguments()[0];
        if ((!empty($args['required']) || in_array('required', $args, true))
            && !isset($context[$Property->getName()])
        ) {
            throw new PropertyRequiredException("Property `\${$Property->getName()}` is required.");
        }

        if (!isset($context[$Property->getName()])) {
            return $Property->getType()?->allowsNull()
                ? null
                : '';
        }

        return preg_replace($args['pattern'], $args['replacement'] ?? '', $value);
    }

    /**
     * Perform a regular expression match.
     *
     * NOTE: If property allows null, null will be returned.
     *
     * ```
     *  #[Describe([
     *      'cast' => [self::class, 'pregMatch'],
     *      'pattern' => '/s/',     // Required
     *      'match_on' => 0         // Index of the $matches to return
     *      'flags' => PREG_UNMATCHED_AS_NULL
     *      'offset' => 0,
     *      'required',             // Throws PropertyRequiredException when value not present.
     *  ])]
     * ```
     */
    public static function pregMatch(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property)
    {
        $args = $Attribute?->getArguments()[0];
        if ((!empty($args['required']) || in_array('required', $args, true))
            && !isset($context[$Property->getName()])
        ) {
            throw new PropertyRequiredException("Property `\${$Property->getName()}` is required.");
        }

        if (!isset($context[$Property->getName()]) && $Property->getType()?->allowsNull()) {
            return null;
        }

        if (!is_string($value)) {
            return $value;
        }

        preg_match($args['pattern'], $value, $matches, $args['flags'] ?? 0, $args['offset'] ?? 0);

        if (isset($args['match_on']) && !isset($matches[$args['match_on']])) {
            return;
        }

        return isset($args['match_on'])
            ? $matches[$args['match_on']]
            : $matches;
    }

    /**
     * Determine if a given value is a valid URL.
     *  ```
     *   #[Describe([
     *       'cast' => [self::class, 'isUrl'],
     *       'protocols' => ['http', 'udp'],            // Optional. Defaults to all.
     *       'on_fail' => [MyAction::class, 'method'],  // Optional. Invoked when validation fails.
     *       'exception' => MyException::class,         // Optional. Throws an exception when not url.
     *       'required',                                // Throws PropertyRequiredException when value not present.
     *   ])]
     *  ```
     */
    public static function isUrl(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): ?string
    {
        $args = $Attribute?->getArguments()[0];
        if ((!empty($args['required']) || in_array('required', $args, true))
            && !isset($context[$Property->getName()])
        ) {
            throw new PropertyRequiredException("Property `\${$Property->getName()}` is required.");
        }

        if (!isset($context[$Property->getName()]) && $Property->getType()?->allowsNull()) {
            return null;
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

    /**
     * Determine if a given value is a valid URL.
     *  ```
     *   #[Describe([
     *       'cast' => [self::class, 'isEmail'],
     *       'on_fail' => [MyAction::class, 'method'],  // Optional. Invoked when validation fails.
     *       'exception' => MyException::class,         // Optional. Throws an exception when not a valid email.
     *       'required',                                // Throws PropertyRequiredException when value not present.
     *   ])]
     *  ```
     */
    public static function isEmail(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): ?string
    {
        $args = $Attribute?->getArguments()[0];
        if ((!empty($args['required']) || in_array('required', $args, true))
            && !isset($context[$Property->getName()])
        ) {
            throw new PropertyRequiredException("Property `\${$Property->getName()}` is required.");
        }

        if (!isset($context[$Property->getName()]) && $Property->getType()?->allowsNull()) {
            return null;
        }

        if (!is_string($value)) {
            if (isset($args['on_fail'])) {
                call_user_func($args['on_fail'], $value, $context, $Attribute, $Property);
            }

            if (isset($args['exception'])) {
                throw new $args['exception'];
            }
        }

        if (!ValidateEmail::isEmail($value)) {
            if (isset($args['on_fail'])) {
                call_user_func($args['on_fail'], $value, $context, $Attribute, $Property);
            }
            if (isset($args['exception'])) {
                throw new $args['exception'];
            }
        }

        return $value;
    }

    /**
     * Determine if a given value is a valid URL.
     *  ```
     *   #[Describe([
     *       'cast' => [self::class, 'isMultiple'],
     *       'of' => 2                                  // The number the value is a multiple of
     *       'on_fail' => [MyAction::class, 'method'],  // Optional. Invoked when validation fails.
     *       'exception' => MyException::class,         // Optional. Throws an exception when not a valid email.
     *       'required',                                // Throws PropertyRequiredException when value not present.
     *   ])]
     *  ```
     */
    public static function isMultiple(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): ?string
    {
        $args = $Attribute?->getArguments()[0];
        if ((!empty($args['required']) || in_array('required', $args, true))
            && !isset($context[$Property->getName()])
        ) {
            throw new PropertyRequiredException("Property `\${$Property->getName()}` is required.");
        }

        if (!isset($context[$Property->getName()]) && $Property->getType()?->allowsNull()) {
            return null;
        }

        if (!is_string($value)) {
            if (isset($args['on_fail'])) {
                call_user_func($args['on_fail'], $value, $context, $Attribute, $Property);
            }

            if (isset($args['exception'])) {
                throw new $args['exception'];
            }
        }

        if ((int)$value % $args['of'] !== 0) {
            if (isset($args['on_fail'])) {
                call_user_func($args['on_fail'], $value, $context, $Attribute, $Property);
            }
            if (isset($args['exception'])) {
                throw new $args['exception'];
            }
        }

        return $value;
    }

    /**
     * Determine if a given value is a valid URL.
     *  ```
     *   #[Describe([
     *       'cast' => [self::class, 'when'],
     *       'eval' => '$value >= $context["value_2"]' // The expression to evaluate.
     *       'true' => [MyAction::class, 'passed'],    // Optional. Invoked when condition is true.
     *       'false' => [MyAction::class, 'failed'],   // Optional. Invoked when condition is true.
     *       'required',                               // Throws PropertyRequiredException when value not present.
     *   ])]
     *  ```
     */
    public static function when(mixed $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): ?string
    {
        $args = $Attribute?->getArguments()[0];
        if ((!empty($args['required']) || in_array('required', $args, true))
            && !isset($context[$Property->getName()])
        ) {
            throw new PropertyRequiredException("Property `\${$Property->getName()}` is required.");
        }

        if (!isset($context[$Property->getName()]) && $Property->getType()?->allowsNull()) {
            return null;
        }

        if (eval("return {$args['eval']};")) {
            if (isset($args['true'])) {
                return call_user_func($args['true'], $value, $context, $Attribute, $Property);
            }
        } elseif (isset($args['false'])) {
            return call_user_func($args['false'], $value, $context, $Attribute, $Property);
        }

        return $value;
    }
}