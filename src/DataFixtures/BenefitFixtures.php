<?php

declare(strict_types=1);

namespace BenefitBundle\DataFixtures;

use BenefitBundle\Entity\Benefit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BenefitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
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

        foreach ($benefits as $benefitData) {
            $benefit = new Benefit();
            $benefit->setName($benefitData['name']);
            $benefit->setDescription($benefitData['description']);
            $benefit->setAmount($benefitData['amount']);
            $benefit->setType($benefitData['type']);
            $benefit->setActive($benefitData['active']);
            $benefit->setRemark($benefitData['remark']);

            $manager->persist($benefit);
        }

        $manager->flush();
    }
}
