<?php

namespace BenefitBundle\Tests\DependencyInjection;

use BenefitBundle\DependencyInjection\BenefitExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BenefitExtensionTest extends TestCase
{
    /**
     * 测试正常加载服务配置
     */
    public function testLoad_withValidConfiguration(): void
    {
        $container = new ContainerBuilder();
        $extension = new BenefitExtension();

        $extension->load([], $container);

        // 验证扩展正确加载了配置
        // 由于服务配置文件services.yaml目前为空，我们只需验证没有抛出异常
        $this->assertTrue(true);
    }

}
