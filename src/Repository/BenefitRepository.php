<?php

declare(strict_types=1);

namespace BenefitBundle\Repository;

use BenefitBundle\Entity\Benefit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;

/**
 * @extends ServiceEntityRepository<Benefit>
 */
#[AsRepository(entityClass: Benefit::class)]
class BenefitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Benefit::class);
    }

    /**
     * @return array<Benefit>
     */
    public function findActiveBenefits(): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.active = :active')
            ->setParameter('active', true)
            ->orderBy('b.createTime', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<Benefit>
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.type = :type')
            ->setParameter('type', $type)
            ->orderBy('b.createTime', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<Benefit>
     */
    public function findActiveByType(string $type): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.type = :type')
            ->andWhere('b.active = :active')
            ->setParameter('type', $type)
            ->setParameter('active', true)
            ->orderBy('b.createTime', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(Benefit $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Benefit $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
