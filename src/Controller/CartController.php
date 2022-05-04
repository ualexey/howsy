<?php

namespace App\Controller;

use App\CartBuilder\SimpleCartBuilder;
use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add", name="addToCart", methods={"POST"})
     */
    public function addToCart(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $client = $entityManager->getRepository(Clients::class)->findOneBy(['id' => $data['clientId']]);

        if (!$client) {
            throw new Exception("Invalid client id");
        }

        if (!isset($data['productCodes'])) {
            throw new Exception("Invalid request");
        }

        $cart = new SimpleCartBuilder($entityManager);
        $cart->getCart($client);
        $cart->addProducts($data['productCodes']);
        $cart->calculateAmount();
        $cart->applyDiscount($client->getDiscount());

        $entityManager->flush();

        $response = new Response();
        $response->setCharset('UTF-8');
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode(['clientId' => $client->getId()]));
        return $response;
    }

    /**
     * @Route("/cart/total/{clientId}", name="getCartTotal", methods={"GET"}))
     */
    public function getCartTotal($clientId, EntityManagerInterface $entityManager): Response
    {

        $client = $entityManager->getRepository(Clients::class)->findOneBy(['id' => $clientId]);

        if (!$client) {
            throw new Exception("Invalid client id");
        }

        $cart = $client->getCart();

        if ($cart) {
            $total = $cart->getTotal();
        } else {
            $total = 0;
        }

        $response = new Response();
        $response->setCharset('UTF-8');
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode(['clientId' => $client->getId(), 'total' => $total]));
        return $response;


    }

}
