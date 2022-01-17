<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $author1 = new Author();
        $author1->setName('Author 1');
        $manager->persist($author1);
        $manager->flush();

        $author2 = new Author();
        $author2->setName('Author 2');
        $manager->persist($author2);
        $manager->flush();

        $author3 = new Author();
        $author3->setName('Author 3');
        $manager->persist($author3);
        $manager->flush();

        $book1 = new Book();
        $book1->setName('Book 1');
        $book1->setAuthors([$author1, $author2]);
        $manager->persist($book1);
        $manager->flush();

        $book2 = new Book();
        $book2->setName('Book 2');
        $book2->setAuthors([$author2, $author3]);
        $manager->persist($book2);
        $manager->flush();

        $book3 = new Book();
        $book3->setName('Book 3');
        $book3->setAuthors([$author1, $author2, $author3]);
        $manager->persist($book3);
        $manager->flush();
    }
}
