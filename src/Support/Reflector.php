<?php

namespace A2Workspace\AutoMount\Support;

use ReflectionClass;
use ReflectionProperty;
use ReflectionNamedType;

class Reflector
{
    /**
     * 取得有型別定義 (type-hinted) 的類別屬性 (class property) 列表。
     *
     * @param  mixed  $class
     * @return array
     */
    public static function getClassTypeHintedProperties($class): array
    {
        $reflector = new ReflectionClass($class);

        return array_filter($reflector->getProperties(), function (ReflectionProperty $property) {
            // 過濾未定義型別的屬性
            if (! $property->hasType()) {
                return false;
            }

            // 過濾靜態屬性
            if ($property->isStatic()) {
                return false;
            }

            // 過濾非類別內定義，額外加入的屬性。
            if (! $property->isDefault()) {
                return false;
            }

            return true;
        });
    }

    /**
     * 取得屬性 (property) 型別定義 (type-hinted) 的類別名稱。
     *
     * 若為 php 的基本型別 (int, bool, string ...) 會回傳 null。
     *
     * @param  \ReflectionProperty  $property
     * @return string|null
     */
    public static function getPropertyHintedClassName(ReflectionProperty $property): ?string
    {
        $type = $property->getType();

        if (! $type instanceof ReflectionNamedType || $type->isBuiltin()) {
            return null;
        }

        $name = $type->getName();

        if ($name === 'self') {
            return null;
        }

        return $name;
    }
}
