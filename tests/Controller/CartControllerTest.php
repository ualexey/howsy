<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;

class CartControllerTest extends WebTestCase
{


    public function testAddProductsDiscountClient(): void
    {
        $client = static::createClient();

        $content = [
            "clientId" => 2,
            "productCodes" => ['P003', 'P004'],
        ];

        $client->request('POST', '/cart/add', [], [], [], json_encode($content));

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertArrayHasKey('clientId', $data);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(2, $data['clientId']);
    }

    public function testAddProductsNoDiscountClient(): void
    {
        $client = static::createClient();

        $content = [
            "clientId" => 1,
            "productCodes" => ['P001', 'P002'],
        ];

        $client->request('POST', '/cart/add', [], [], [], json_encode($content));

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertArrayHasKey('clientId', $data);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $data['clientId']);
    }

    /**
     * @depends testAddProductsDiscountClient
     */
    public function testGetCartTotalDiscountSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart/total/2');

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertArrayHasKey('clientId', $data);
        $this->assertArrayHasKey('total', $data);
        $this->assertEquals(121.05, $data['total']);
    }

    /**
     * @depends testAddProductsNoDiscountClient
     */
    public function testGetCartTotalNoDiscountSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart/total/1');

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertArrayHasKey('clientId', $data);
        $this->assertArrayHasKey('total', $data);
        $this->assertEquals(300, $data['total']);
    }

    /**
     * @depends testAddProductsNoDiscountClient
     */
    public function testAddDuplicateProduct(): void
    {
        $client = static::createClient();

        $content = [
            "clientId" => 1,
            "productCodes" => ['P001', 'P002'],
        ];

        $client->request('POST', '/cart/add', [], [], [], json_encode($content));

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertArrayHasKey('clientId', $data);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $data['clientId']);
    }

    /**
     * @depends testAddDuplicateProduct
     */
    public function testGetDuplicateProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart/total/1');

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertArrayHasKey('clientId', $data);
        $this->assertArrayHasKey('total', $data);
        $this->assertEquals(300, $data['total']);
    }


    public function testException(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart/total/96546584466464');

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Invalid client id', $data['error']);
    }



}
