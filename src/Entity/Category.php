<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: MonthPayment::class)]
    private Collection $monthPayments;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->monthPayments = new ArrayCollection();
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MonthPayment>
     */
    public function getMonthPayments(): Collection
    {
        return $this->monthPayments;
    }

    public function addMonthPayment(MonthPayment $monthPayment): self
    {
        if (!$this->monthPayments->contains($monthPayment)) {
            $this->monthPayments->add($monthPayment);
            $monthPayment->setCategory($this);
        }

        return $this;
    }

    public function removeMonthPayment(MonthPayment $monthPayment): self
    {
        if ($this->monthPayments->removeElement($monthPayment)) {
            // set the owning side to null (unless already changed)
            if ($monthPayment->getCategory() === $this) {
                $monthPayment->setCategory(null);
            }
        }

        return $this;
    }
}
