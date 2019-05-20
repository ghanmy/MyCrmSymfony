<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
         $user = new User();
         $user->setNom("admin");
         $user->setPrenom("admin");
        $user->setEmail("admin@admin.fr");
        $user->setPays("Tunisie");
        $user->setTel1("25487956");
        $user->setTel2("25478963");
        $user->setPassword($this->encoder->encodePassword($user,"mghanmy"));
        $user->setRoles(['ROLE_ADMIN']);


        $manager->persist($user);

        $manager->flush();
    }
}
