<?php

declare(strict_types=1);

namespace BenefitBundle\Entity;

use BenefitBundle\Model\BenefitResource;
use BenefitBundle\Repository\BenefitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

#[ORM\Entity(repositoryClass: BenefitRepository::class)]
#[ORM\Table(name: 'benefit', options: ['comment' => '福利管理'])]
class Benefit implements BenefitResource, \Stringable
{
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100, options: ['comment' => '福利名称'])]
    private string $name;

    #[Assert\Length(max: 500)]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '福利描述'])]
    private ?string $description = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['comment' => '福利金额', 'default' => '0.00'])]
    private string $amount = '0.00';

    #[Assert\Length(max: 50)]
    #[ORM\Column(type: Types::STRING, length: 50, nullable: true, options: ['comment' => '福利类型'])]
    private ?string $type = null;

    #[Assert\Type(type: 'bool')]
    #[IndexColumn(name: 'active')]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否激活', 'default' => 1])]
    private ?bool $active = true;

    #[Assert\Length(max: 1000)]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '备注'])]
    private ?string $remark = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    public function __toString(): string
    {
        return sprintf('Benefit[%d]: %s', $this->id, $this->name ?? 'Unnamed');
    }
}
