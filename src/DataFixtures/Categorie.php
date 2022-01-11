<?php

namespace App\DataFixtures;

use App\Entity\Categorie as EntityCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Categorie extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $categorie = new EntityCategorie;
            $categorie->setLibelle('categorie$i');
            $manager->persist($categorie);
        }
        $manager->flush();
    }
}
