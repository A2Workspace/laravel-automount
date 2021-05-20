<?php

namespace A2Workspace\AutoMount\Support;

use ReflectionProperty;

class Injector
{
    /**
     * 給定目標物件，嘗試對指定屬性進行依賴注入。
     *
     * 可指定 $abstract 參數作為要注入的依賴名稱。
     * 預設將會以目標物件中，屬性定義的型別去生成依賴實體。
     *
     * @param  object  $object
     * @param  \ReflectionProperty|string  $property
     * @param  string|null  $abstract
     * @return void
     */
    public static function injectPropertyDependency(object $object, $property, $abstract = null)
    {
        if (! ($property instanceof ReflectionProperty)) {
            $property = new ReflectionProperty($object, $property);
        }

        if (is_null($abstract)) {
            $abstract = Reflector::getPropertyHintedClassName($property);
        }

        $instance = static::transformDependency($abstract);

        if (! is_null($instance)) {
            $property->setAccessible(true);
            $property->setValue($object, $instance);
        }
    }

    /**
     * 嘗試將給定的類別名稱轉換實體。
     *
     * @param  string  $className
     * @return mixed
     */
    public static function transformDependency($className)
    {
        if ($className) {
            return app($className);
        }
    }
}
