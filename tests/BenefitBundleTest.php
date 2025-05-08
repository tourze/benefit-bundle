<?php

namespace BenefitBundle\Tests;

use BenefitBundle\BenefitBundle;
use PHPUnit\Framework\TestCase;

class BenefitBundleTest extends TestCase
{
    /**
     * 测试Bundle实例化
     */
    public function testBundleInstantiation(): void
    {
        $bundle = new BenefitBundle();
        $this->assertInstanceOf(BenefitBundle::class, $bundle);
    }
}
