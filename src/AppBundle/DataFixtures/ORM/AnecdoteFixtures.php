<?php

namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Anecdote;
use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LevelFixtures extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
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


        $cat1 = new Category();
        $cat1->setName('cat-test');
        for ($i = 0 ; $i < 1000 ; $i++) {
            $anecdote = new Anecdote();
            $anecdote->setDescription('desc ' . $i);
            $anecdote->setCategory($cat1);
            $manager->persist($anecdote);
        }
        $manager->flush();
    }

}