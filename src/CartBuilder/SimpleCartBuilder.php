<?php

namespace App\CartBuilder;

use App\Entity\Cart;
use App\Entity\Clients;
use App\Entity\Products;
use App\Interfaces\CartBuilderInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class SimpleCartBuilder implements CartBuilderInterface
{

    protected $cart;
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Clients $client
     * @return CartBuilderInterface
     */
    public function getCart(Clients $client): CartBuilderInterface
    {

        $this->cart = $client->getCart();

        if (!$this->cart) {

            $this->cart = new Cart();
            $client->setCart($this->cart);

            $this->entityManager->persist($this->cart);
            $this->entityManager->persist($client);
         }

        return $this;
    }

    /**
     * @param array $products
     * @return CartBuilderInterface
     */
    public function addProducts(array $products): CartBuilderInterface
    {
        foreach ($products as $code) {
            $product = $this->entityManager->getRepository(Products::class)->findOneBy(['code' => $code]);
            if (!$product) {
                continue;
            } else {

                // Create unique array with item codes in cart
                $unique = [];
                foreach ($this->cart->getProducts()->getValues() as $item) {
                    $unique[] = $item->getCode();
                }

                // Check if product is unique, then add it to cart
                if (!in_array($product->getCode(), $unique)) {
                    $this->cart->addProduct($product);
                }
            }
        }

        return $this;
    }

    /**
     * @return CartBuilderInterface
     */
    public function calculateAmount(): CartBuilderInterface
    {
        $products = $this->cart->getProducts();

        if ($products) {
            $total = 0;
            foreach ($products as $product) {
                $total += $product->getPrice();
            }
            $this->cart->setTotal($total);
        }

        return $this;
    }

    /**
     * @return CartBuilderInterface
     */
    public function applyDiscount(Collection $discounts): CartBuilderInterface
    {

//        if ($discounts) {
            $percent = 0;
            foreach ($discounts as $discount) {
                $percent += $discount->getPercentAmount();
            }

            $this->cart->setDiscount($percent);
            $this->cart->setTotal($this->cart->getTotal() - ($this->cart->getTotal() * $this->cart->getDiscount() / 100));
//        }

        return $this;
    }
}