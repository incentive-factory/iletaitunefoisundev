<?php

declare(strict_types=1);

namespace App\Admin\Doctrine\DataFixtures;

use App\Admin\Entity\Administrator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdministratorFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; ++$i) {
            $manager->persist($this->createAdministrator($i));
        }
        $manager->flush();
    }

    private function createAdministrator(int $index): Administrator
    {
        $user = new Administrator();
        $user->setEmail(sprintf('admin+%d@email.com', $index));
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));

        return $user;
    }
}
