<?php

namespace Zerotoprod\DataModelHelper;

use ReflectionAttribute;
use ReflectionProperty;

trait DataModelHelper
{
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