<?php

namespace App\Entity;

use App\Repository\BalanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BalanceRepository::class)]
class Balance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount_frozen = null;

    #[ORM\OneToMany(mappedBy: 'balance', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'balance', targetEntity: Wages::class)]
    private Collection $wages;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->wages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountFrozen(): ?string
    {
        return $this->amount_frozen;
    }

    public function setAmountFrozen(string $amount_frozen): self
    {
        $this->amount_frozen = $amount_frozen;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setBalance($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getBalance() === $this) {
                $user->setBalance(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wages>
     */
    public function getWages(): Collection
    {
        return $this->wages;
    }

    public function addWage(Wages $wage): self
    {
        if (!$this->wages->contains($wage)) {
            $this->wages->add($wage);
            $wage->setBalance($this);
        }

        return $this;
    }

    public function removeWage(Wages $wage): self
    {
        if ($this->wages->removeElement($wage)) {
            // set the owning side to null (unless already changed)
            if ($wage->getBalance() === $this) {
                $wage->setBalance(null);
            }
        }

        return $this;
    }
}
