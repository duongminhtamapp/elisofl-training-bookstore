<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
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

        $order1 = new Order();
        $order1->setPhone('0352874390');
        $order1->setAddress('Address 1');
        $order1->setStatus(0);
        $order1->setBooks([$book1, $book2]);
        $manager->persist($order1);
        $manager->flush();

        $order2 = new Order();
        $order2->setPhone('0352874391');
        $order2->setAddress('Address 2');
        $order2->setStatus(1);
        $order2->setBooks([$book2, $book3]);
        $manager->persist($order2);
        $manager->flush();
    }
}
