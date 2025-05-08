<?php

namespace BenefitBundle\Tests\Integration;

use BenefitBundle\BenefitBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BenefitBundleIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return IntegrationTestKernel::class;
    }

    /**
     * 测试Bundle正确加载
     */
    public function testBundleLoaded(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        // 验证容器包含必要的参数
        $this->assertTrue($container->hasParameter('kernel.bundles'));

        // 验证BenefitBundle已注册
        $bundles = $container->getParameter('kernel.bundles');
        $this->assertArrayHasKey('BenefitBundle', $bundles);
        $this->assertEquals(BenefitBundle::class, $bundles['BenefitBundle']);
    }

    /**
     * 测试配置加载
     */
    public function testConfigLoaded(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        // 框架配置测试
        $this->assertEquals('TEST_SECRET', $container->getParameter('kernel.secret'));
        $this->assertTrue($container->getParameter('kernel.debug'));
    }

    /**
     * 测试环境清理
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        // 清理Kernel实例
        self::ensureKernelShutdown();
        self::$kernel = null;
    }
}
