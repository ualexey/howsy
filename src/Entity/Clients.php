<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientsRepository::class)
 */
class Clients
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Discounts::class)
     */
    private $discount;

    /**
     * @ORM\OneToOne(targetEntity=Cart::class, inversedBy="client", cascade={"persist", "remove"})
     */
    private $cart;

    public function __construct()
    {
        $this->discount = new ArrayCollection();
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

    /**
     * @return Collection<int, Discounts>
     */
    public function getDiscount(): Collection
    {
        return $this->discount;
    }

    public function addDiscount(Discounts $discount): self
    {
        if (!$this->discount->contains($discount)) {
            $this->discount[] = $discount;
        }

        return $this;
    }

    public function removeDiscount(Discounts $discount): self
    {
        $this->discount->removeElement($discount);

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }
}
