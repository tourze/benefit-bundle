<?php

namespace BenefitBundle\Tests\Model;

use BenefitBundle\Model\BenefitResource;
use PHPUnit\Framework\TestCase;

class BenefitResourceTest extends TestCase
{
    /**
     * 测试接口存在性
     */
    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(BenefitResource::class));
    }

    /**
     * 测试接口可实现
     */
    public function testInterfaceImplementable(): void
    {
        // 创建匿名类实现接口
        $resource = new class implements BenefitResource {
        };

        $this->assertInstanceOf(BenefitResource::class, $resource);
    }
}
