<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CategoryType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

// DependentFixtureInterface garantit que UserFixtures est chargée AVANT DashboardFixtures
class DashboardFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'Moteur thermique'=> [
                'Moteur',
                'Boite de vitesse',
            ],
            'GMPE' => [
                'Moteur',
                'Stator',
                'Rotor',
                'PEB',
                'Réducteur',
            ],
            'E-Motor' => [
                'Moteur AFM',
                'Carter C-MOT',
                'Carter C-FERM',
                'Stator AFM',
                'Rotor AFM',
            ],
            'Module' => [
                'Modules GPEC Batteries',
                'Modules PVAL',
            ],
            'Batterie' => [],// pas de types pour l'instant
        ];

        foreach ($data as $categoryName => $types) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);

            foreach ($types as $typeName) {
                $type = new CategoryType();
                $type->setName($typeName);
                $type->setCategory($category);
                $manager->persist($type);
            }
        }

        $manager->flush();
    }

    // Déclare que UserFixtures doit être chargée en premier
    public function getDependencies(): array 
    {
        return [UserFixtures::class];
    }
}
