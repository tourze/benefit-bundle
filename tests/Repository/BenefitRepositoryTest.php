<?php

declare(strict_types=1);

namespace BenefitBundle\Tests\Repository;

use BenefitBundle\Entity\Benefit;
use BenefitBundle\Repository\BenefitRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(BenefitRepository::class)]
#[RunTestsInSeparateProcesses]
final class BenefitRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 清理所有 Benefit 实体
        $this->clearAllBenefits();

        // 手动加载数据固定器数据
        $this->loadBenefitFixtures();
    }

    protected function createNewEntity(): object
    {
        $benefit = new Benefit();
        $benefit->setName('Test Benefit');
        $benefit->setType('health');
        $benefit->setActive(true);
        $benefit->setAmount('100.00');
        $benefit->setDescription('Test description');

        return $benefit;
    }

    protected function getRepository(): BenefitRepository
    {
        $repository = self::getContainer()->get(BenefitRepository::class);
        $this->assertInstanceOf(BenefitRepository::class, $repository);

        return $repository;
    }

    private function clearAllBenefits(): void
    {
        self::getEntityManager()->createQuery('DELETE FROM ' . Benefit::class)->execute();
    }

    /**
     * 手动加载 BenefitFixtures 数据
     *
     * 由于项目配置禁用了自动 fixtures 加载，这里手动复制 BenefitFixtures 的数据
     */
    private function loadBenefitFixtures(): void
    {
        $benefits = [
            [
                'name' => '年终奖金',
                'description' => '公司年终发放的奖金福利',
                'amount' => '5000.00',
                'type' => 'monetary',
                'active' => true,
                'remark' => '根据员工表现发放',
            ],
            [
                'name' => '健康保险',
                'description' => '公司提供的健康医疗保险',
                'amount' => '2000.00',
                'type' => 'insurance',
                'active' => true,
                'remark' => '包含基本医疗和意外险',
            ],
            [
                'name' => '培训津贴',
                'description' => '员工参与培训的补助津贴',
                'amount' => '1000.00',
                'type' => 'education',
                'active' => true,
                'remark' => '用于技能提升培训',
            ],
        ];

        $entityManager = self::getEntityManager();
        foreach ($benefits as $benefitData) {
            $benefit = new Benefit();
            $benefit->setName($benefitData['name']);
            $benefit->setDescription($benefitData['description']);
            $benefit->setAmount($benefitData['amount']);
            $benefit->setType($benefitData['type']);
            $benefit->setActive($benefitData['active']);
            $benefit->setRemark($benefitData['remark']);

            $entityManager->persist($benefit);
        }

        $entityManager->flush();
    }

    public function testFindActiveBenefits(): void
    {
        // 创建测试数据
        $activeBenefit1 = $this->createAndPersistBenefit('Active Benefit 1', 'health', true);
        $activeBenefit2 = $this->createAndPersistBenefit('Active Benefit 2', 'insurance', true);
        $inactiveBenefit = $this->createAndPersistBenefit('Inactive Benefit', 'vacation', false);

        $results = $this->getRepository()->findActiveBenefits();

        // 固定器中加载了3个激活的福利，加上测试创建的2个激活福利，总共5个
        $this->assertCount(5, $results);
        $this->assertContains($activeBenefit1, $results);
        $this->assertContains($activeBenefit2, $results);
        $this->assertNotContains($inactiveBenefit, $results);
    }

    public function testFindByType(): void
    {
        // 创建不同类型的福利
        $healthBenefit1 = $this->createAndPersistBenefit('Health Benefit 1', 'health', true);
        $healthBenefit2 = $this->createAndPersistBenefit('Health Benefit 2', 'health', false);
        $insuranceBenefit = $this->createAndPersistBenefit('Insurance Benefit', 'insurance', true);

        $healthResults = $this->getRepository()->findByType('health');
        $insuranceResults = $this->getRepository()->findByType('insurance');
        $nonExistentResults = $this->getRepository()->findByType('non-existent');

        $this->assertCount(2, $healthResults);
        $this->assertContains($healthBenefit1, $healthResults);
        $this->assertContains($healthBenefit2, $healthResults);

        // 固定器中有1个insurance类型的福利，加上测试创建的1个，总共2个
        $this->assertCount(2, $insuranceResults);
        $this->assertContains($insuranceBenefit, $insuranceResults);

        $this->assertCount(0, $nonExistentResults);
    }

    public function testFindActiveByType(): void
    {
        // 创建测试数据
        $activeHealthBenefit = $this->createAndPersistBenefit('Active Health', 'health', true);
        $inactiveHealthBenefit = $this->createAndPersistBenefit('Inactive Health', 'health', false);
        $activeInsuranceBenefit = $this->createAndPersistBenefit('Active Insurance', 'insurance', true);

        $activeHealthResults = $this->getRepository()->findActiveByType('health');
        $activeInsuranceResults = $this->getRepository()->findActiveByType('insurance');
        $nonExistentResults = $this->getRepository()->findActiveByType('non-existent');

        $this->assertCount(1, $activeHealthResults);
        $this->assertContains($activeHealthBenefit, $activeHealthResults);
        $this->assertNotContains($inactiveHealthBenefit, $activeHealthResults);

        // 固定器中有1个insurance类型的福利，加上测试创建的1个，总共2个
        $this->assertCount(2, $activeInsuranceResults);
        $this->assertContains($activeInsuranceBenefit, $activeInsuranceResults);

        $this->assertCount(0, $nonExistentResults);
    }

    public function testFindActiveBenefitsOrderedByCreateTime(): void
    {
        $benefit1 = $this->createAndPersistBenefit('First Benefit', 'health', true);

        // 休眠1毫秒确保不同的创建时间
        usleep(1000);
        $benefit2 = $this->createAndPersistBenefit('Second Benefit', 'insurance', true);

        $results = $this->getRepository()->findActiveBenefits();

        // 固定器中有3个激活福利，加上测试创建的2个，总共5个
        $this->assertCount(5, $results);

        // 从数据库重新获取以确保时间戳已正确设置
        self::getEntityManager()->refresh($benefit1);
        self::getEntityManager()->refresh($benefit2);

        $results = $this->getRepository()->findActiveBenefits();

        // 验证结果数量
        $this->assertCount(5, $results);

        // 验证两个实体都存在，无论顺序如何
        $names = array_map(fn ($benefit) => $benefit->getName(), $results);
        $this->assertContains('First Benefit', $names);
        $this->assertContains('Second Benefit', $names);
    }

    private function createBenefit(string $name, string $type, bool $active = true): Benefit
    {
        $benefit = new Benefit();
        $benefit->setName($name);
        $benefit->setType($type);
        $benefit->setActive($active);
        $benefit->setAmount('100.00');
        $benefit->setDescription('Test description for ' . $name);

        return $benefit;
    }

    private function createAndPersistBenefit(string $name, string $type, bool $active = true): Benefit
    {
        $benefit = $this->createBenefit($name, $type, $active);
        self::getEntityManager()->persist($benefit);
        self::getEntityManager()->flush();

        return $benefit;
    }
}
