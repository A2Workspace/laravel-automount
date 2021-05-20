<?php

namespace Tests\Unit;

use Tests\TestCase;
use A2Workspace\AutoMount\AutoMountDependencies;

class AutoMountDependenciesTest extends TestCase
{
    public function testBasicResolution()
    {
        $service = new ServiceStub;

        $this->assertInstanceOf(ServiceDependentStub::class, $service->inner);
    }

    public function testInheritedDependenciesResolution()
    {
        $service = new ServiceInheritedStub;

        $this->assertInstanceOf(ServiceDependentStub::class, $service->inner);
    }

    public function testProtectedDependenciesResolution()
    {
        $service = new ServiceProtectedStub;

        $this->assertInstanceOf(ServiceDependentStub::class, $service->getInner());
    }

    public function testInheritedProtectedDependenciesResolution()
    {
        $service = new ServiceInheritedProtectedStub;

        $this->assertInstanceOf(ServiceDependentStub::class, $service->getInner());
    }
}

class ServiceDependentStub
{
    //
}

class ServiceStub
{
    use AutoMountDependencies;

    public ServiceDependentStub $inner;
}

class ServiceInheritedStub extends ServiceStub
{
    //
}

class ServiceProtectedStub
{
    use AutoMountDependencies;

    protected ServiceDependentStub $inner;

    public function getInner()
    {
        return $this->inner;
    }
}

class ServiceInheritedProtectedStub extends ServiceProtectedStub
{
    //
}
