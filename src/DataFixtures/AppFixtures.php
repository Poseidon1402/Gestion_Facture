<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client1 = new Client();
        $client1->setNumcli('CL01')
                ->setNom('RABE');
        $client2 = new Client();
        $client2->setNumcli('CL02')
                ->setNom('Ilo');
        $client3 = new Client();
        $client3->setNumcli('CL03')
                ->setNom('Jean');
        $client4 = new Client();
        $client4->setNumcli('CL04');
        $client4        ->setNom('RAVAO');
        
        $manager->persist($client1);
        $manager->persist($client2);
        $manager->persist($client3);
        $manager->persist($client4);
        $manager->flush();
    }
}
