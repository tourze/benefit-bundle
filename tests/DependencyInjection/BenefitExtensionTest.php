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

    /**
     * 测试加载不存在的配置文件时抛出异常
     */
    public function testLoad_withInvalidPath_shouldThrowException(): void
    {
        $this->markTestSkipped('这个测试会修改BenefitExtension，不符合"不得随意修改src目录下的代码"的要求');

        /*
        // 这个测试需要修改BenefitExtension类中的FileLocator路径，不符合要求
        $container = new ContainerBuilder();
        $extension = new BenefitExtension();
        
        // 修改FileLocator路径指向不存在的目录
        $this->expectException(FileLocatorFileNotFoundException::class);
        $extension->load([], $container);
        */
    }
}
