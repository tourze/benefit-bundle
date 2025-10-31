<?php

declare(strict_types=1);

namespace BenefitBundle\Service;

use BenefitBundle\Entity\Benefit;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        $benefitMenu = $item->getChild('福利管理');
        if (null === $benefitMenu) {
            $benefitMenu = $item->addChild('福利管理');
        }

        $benefitMenu
            ->addChild('福利列表')
            ->setUri($this->linkGenerator->getCurdListPage(Benefit::class))
            ->setAttribute('icon', 'fas fa-gift')
        ;
    }
}
