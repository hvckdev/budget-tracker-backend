<?php

namespace App\Entity;

use App\Repository\GoalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoalRepository::class)]
class Goal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'goals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price_per_month = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Product $product = null;

    #[ORM\OneToOne(mappedBy: 'goal', cascade: ['persist', 'remove'])]
    private ?Purchase $purchase = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPricePerMonth(): ?string
    {
        return $this->price_per_month;
    }

    public function setPricePerMonth(string $price_per_month): self
    {
        $this->price_per_month = $price_per_month;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): self
    {
        // unset the owning side of the relation if necessary
        if ($purchase === null && $this->purchase !== null) {
            $this->purchase->setGoal(null);
        }

        // set the owning side of the relation if necessary
        if ($purchase !== null && $purchase->getGoal() !== $this) {
            $purchase->setGoal($this);
        }

        $this->purchase = $purchase;

        return $this;
    }
}
