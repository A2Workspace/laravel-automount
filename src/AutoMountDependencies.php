<?php

namespace A2Workspace\AutoMount;

use ReflectionProperty;
use A2Workspace\AutoMount\Support\Injector;
use A2Workspace\AutoMount\Support\Reflector;

trait AutoMountDependencies
{
    /**
     * 在類別初始時自動掛載依賴。
     */
    public function __construct()
    {
        $this->mountDependencies();
    }

    /**
     * 解析所有類別的屬性，嘗試進行依賴注入。
     *
     * 若開啟 override 則會強制覆蓋已被初始的屬性。
     *
     * @param  bool  $override
     * @return void
     */
    protected function mountDependencies($override = false)
    {
        $properties = Reflector::getClassTypeHintedProperties(static::class);

        foreach ($properties as $property) {
            $this->mountPropertyDependency($property, $override);
        }
    }

    /**
     * 指定屬性或屬性名稱；嘗試解析它定義的型別，並進行依賴注入。
     *
     * @param  \ReflectionProperty|string  $property
     * @param  bool  $override
     * @return void
     *
     * @throws \ReflectionException
     */
    protected function mountPropertyDependency($property, $override = false)
    {
        if (! ($property instanceof ReflectionProperty)) {
            $property = new ReflectionProperty(static::class, $property);
        }

        // 打開，讓我看看。
        $property->setAccessible(true);
        if (! $override && $property->isInitialized($this)) {
            // 若類別屬性已被初始，則略過處理。
            return;
        }

        Injector::injectPropertyDependency($this, $property);
    }
}
