<?php

namespace App\Command;

use App\Entity\Clients;
use App\Entity\Discounts;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDbdataCommand extends Command
{

    protected static $defaultName = 'app:create-dbdata';
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createProducts();
        $this->createDiscounts();
        $this->createClients();

        $output->writeLn('Done.');

        return Command::SUCCESS;
    }


    private function createProducts()
    {

        $prodArray = [
            [
                'code' => 'P001',
                'name' => 'Photography',
                'price' => 200,
            ],
            [
                'code' => 'P002',
                'name' => 'Floorplan',
                'price' => 100,
            ],
            [
                'code' => 'P003',
                'name' => 'Gas Certificate',
                'price' => 83.50,
            ],
            [
                'code' => 'P004',
                'name' => 'EICR Certificate',
                'price' => 51.00,
            ],
        ];
        foreach ($prodArray as $prodVal) {
            $exist = $this->entityManager->getRepository(Products::class)->findOneBy(['code' => $prodVal['code']]);
            if ($exist) {
                continue;
            }
            $product = new Products();
            $product->setCode($prodVal['code']);
            $product->setName($prodVal['name']);
            $product->setPrice($prodVal['price']);
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }
    }

    private function createDiscounts()
    {

        $discArray = [
            [
                'name' => '12-month contract',
                'percentAmount' => 10,
            ],
        ];

        foreach ($discArray as $discVal) {
            $exist = $this->entityManager->getRepository(Discounts::class)->findOneBy(['name' => $discVal['name']]);
            if ($exist) {
                continue;
            }
            $discount = new Discounts();
            $discount->setName($discVal['name']);
            $discount->setPercentAmount($discVal['percentAmount']);
            $this->entityManager->persist($discount);
            $this->entityManager->flush();
        }
    }


    private function createClients()
    {

        $cliArray = [
            [
                'name' => 'John',
                'discount' => '',
            ],
            [
                'name' => 'Mike',
                'discount' => '12-month contract',
            ],
        ];

        foreach ($cliArray as $cliVal) {
            $exist = $this->entityManager->getRepository(Clients::class)->findOneBy(['name' => $cliVal['name']]);
            if ($exist) {
                continue;
            }

            $client = new Clients();
            $client->setName($cliVal['name']);

            $discount = $this->entityManager->getRepository(Discounts::class)->findOneBy(['name' => $cliVal['discount']]);
            if ($discount) {
                $client->addDiscount($discount);
            }

            $this->entityManager->persist($client);
            $this->entityManager->flush();
        }
    }

}