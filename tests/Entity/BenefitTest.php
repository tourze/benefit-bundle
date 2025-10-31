<?php

declare(strict_types=1);

namespace BenefitBundle\Tests\Entity;

use BenefitBundle\Entity\Benefit;
use BenefitBundle\Model\BenefitResource;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Benefit::class)]
final class BenefitTest extends AbstractEntityTestCase
{
    private Benefit $benefit;

    protected function createEntity(): object
    {
        return new Benefit();
    }

    protected function setUp(): void
    {
        $this->benefit = new Benefit();
    }

    /**
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'name' => ['name', '年终奖金'],
            'description' => ['description', '公司年终福利发放'],
            'amount' => ['amount', '1500.50'],
            'type' => ['type', 'monetary'],
            'active' => ['active', false],
            'remark' => ['remark', '特殊福利项目备注'],
        ];
    }

    public function testBenefitImplementsBenefitResource(): void
    {
        $this->assertInstanceOf(BenefitResource::class, $this->benefit);
    }

    public function testBenefitIsStringable(): void
    {
        $this->assertInstanceOf(\Stringable::class, $this->benefit);
    }

    public function testInitialValues(): void
    {
        $this->assertSame(0, $this->benefit->getId());
        $this->assertSame('0.00', $this->benefit->getAmount());
        $this->assertTrue($this->benefit->isActive());
        $this->assertNull($this->benefit->getDescription());
        $this->assertNull($this->benefit->getType());
        $this->assertNull($this->benefit->getRemark());
    }

    public function testNameSetterAndGetter(): void
    {
        $name = '年终奖金';
        $this->benefit->setName($name);

        $this->assertSame($name, $this->benefit->getName());
    }

    public function testDescriptionSetterAndGetter(): void
    {
        $description = '公司年终福利发放';
        $this->benefit->setDescription($description);

        $this->assertSame($description, $this->benefit->getDescription());
    }

    public function testAmountSetterAndGetter(): void
    {
        $amount = '1500.50';
        $this->benefit->setAmount($amount);

        $this->assertSame($amount, $this->benefit->getAmount());
    }

    public function testTypeSetterAndGetter(): void
    {
        $type = 'monetary';
        $this->benefit->setType($type);

        $this->assertSame($type, $this->benefit->getType());
    }

    public function testActiveSetterAndGetter(): void
    {
        $this->benefit->setActive(false);

        $this->assertFalse($this->benefit->isActive());
    }

    public function testRemarkSetterAndGetter(): void
    {
        $remark = '特殊福利项目备注';
        $this->benefit->setRemark($remark);

        $this->assertSame($remark, $this->benefit->getRemark());
    }

    public function testToString(): void
    {
        $name = '健康保险';
        $this->benefit->setName($name);

        $expected = 'Benefit[0]: ' . $name;
        $this->assertSame($expected, (string) $this->benefit);
    }

    public function testToStringWithoutName(): void
    {
        $expected = 'Benefit[0]: Unnamed';
        $this->assertSame($expected, (string) $this->benefit);
    }
}
