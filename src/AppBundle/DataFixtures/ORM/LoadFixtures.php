<?php

namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Anecdote;
use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadFixtures extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadAnecdotes($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setEmail('admin@ici.net');
        $adminUser->setRoles(array('ROLE_ADMIN'));
        $encodedPassword = $passwordEncoder->encodePassword($adminUser, 'password');
        $adminUser->setPassword($encodedPassword);
        $manager->persist($adminUser);

        $simpleUser = new User();
        $simpleUser->setUsername('user');
        $simpleUser->setEmail('user@ici.net');
        $encodedPassword = $passwordEncoder->encodePassword($simpleUser, 'password');
        $simpleUser->setPassword($encodedPassword);
        $manager->persist($simpleUser);

        $manager->flush();
    }


    public function loadAnecdotes($manager)
    {
        $names = array(
          'Argent',
          'Travail',
          'Enfants',
          'Santé',
          'Sexe',
          'Animaux',
          'Amour',
          'Inclassable'
        );
        foreach ($names as $name) {
            $category = new Category();
            $category ->setName($name);
            $manager->persist($category );
        }
        $manager->flush();


        /*$cat1 = new Category();
        $cat1->setName('cat-test');
        for ($i = 0 ; $i < 1000 ; $i++) {
            $anecdote = new Anecdote();
            $anecdote->setDescription('desc ' . $i);
            $anecdote->setCategory($cat1);
            $manager->persist($anecdote);
        }
        $manager->flush();*/
    }

}