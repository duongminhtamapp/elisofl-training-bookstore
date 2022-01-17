<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $author = new Author();
        $author->setName('Author 1');
        $manager->persist($author);
        $manager->flush();

        $author = new Author();
        $author->setName('Author 2');
        $manager->persist($author);
        $manager->flush();

        $author = new Author();
        $author->setName('Author 3');
        $manager->persist($author);
        $manager->flush();
    }
}
