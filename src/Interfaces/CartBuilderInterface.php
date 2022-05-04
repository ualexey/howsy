<?php

namespace App\Interfaces;

use App\Entity\Clients;
use Doctrine\Common\Collections\Collection;

interface CartBuilderInterface
{

    /**
     * @param Clients $client
     * @return CartBuilderInterface
     */
    public function getCart(Clients $client): CartBuilderInterface;

    /**
     * @param array $products
     * @return CartBuilderInterface
     */
    public function addProducts(array $products): CartBuilderInterface;

    /**
     * @return CartBuilderInterface
     */
    public function calculateAmount(): CartBuilderInterface;

    /**
     * @return CartBuilderInterface
     */
    public function applyDiscount(Collection $discounts): CartBuilderInterface;


}