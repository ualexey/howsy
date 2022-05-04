<?php

namespace App\Entity;

use App\Repository\DiscountsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountsRepository::class)
 */
class Discounts
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
     * @ORM\Column(type="float")
     */
    private $percentAmount;


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

    public function getPercentAmount(): ?float
    {
        return $this->percentAmount;
    }

    public function setPercentAmount(float $percentAmount): self
    {
        $this->percentAmount = $percentAmount;

        return $this;
    }

}
