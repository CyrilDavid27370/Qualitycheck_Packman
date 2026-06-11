<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{   
    // Constantes pour référencer les utilisateurs
    public const ANALYSTE_REF = 'user-analyste';
    public const ADMIN_REF = 'user-admin';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        // ── Analyste Qualité ───────────────────────────────────
        $analyste = new User();
        $analyste->setMatricule('A670867');
        $analyste->setFirstname('Christophe');
        $analyste->setLastname('Aviceau');
        $analyste->setRoles(['ROLE_USER']);
        $analyste->setActive('true');
        $analyste->setPassword(
            $this->passwordHasher->hashPassword($analyste, 'Test1234')
        );
        $manager->persist($analyste);

        // Référence utilisable depuis d'autres fixtures
        $this->addReference(self::ANALYSTE_REF, $analyste);

        // ── Administrateur ─────────────────────────────────────
        $admin = new User;
        $admin->setMatricule('A670997');
        $admin->setFirstname('Cyril');
        $admin->setLastname('David');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setActive('true');
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'Admin1234')
        );
        $manager->persist($admin);

         // Référence utilisable depuis d'autres fixtures
        $this->addReference(self::ADMIN_REF, $admin);

        $manager->flush();
    }
}
