<?php

namespace App\Tests;

use App\DataFixtures\AuthorFixtures;
use App\DataFixtures\BookFixtures;
use App\Repository\BookRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class BookControllerTest extends ApiTestCase
{
    /**
     * Test GET /api/books
     *
     * @return void
     */
    public function testList(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            BookFixtures::class
        ]);

        // get data from api
        $this->client->request('GET', '/api/books');
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();

        // get data from query
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $books = $bookRepository->findAll();
        $this->assertSameSize($books, json_decode($response->getContent())); // check response size
    }

    /**
     * Test POST /api/books
     *
     * @return void
     */
    public function testCreate(): void
    {
        // create Author test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AuthorFixtures::class
        ]);

        $data = array(
            'name' => 'Book test',
            'authors' => [1, 2, 3]

        );
        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('POST', '/api/books', $data);
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertNotNull(json_decode($response->getContent())->id); // response data is correct

        // get data from query
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->find(json_decode($response->getContent())->id);
        $this->assertNotNull($book); // record exist on database
        $this->assertEquals('Book test', $book->getName()); // record exist on database
        $this->assertCount(3, $book->getAuthors()); // record exist on database
    }

    /**
     * GET POST /api/books/{id}
     *
     * @return void
     */
    public function testShow(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            BookFixtures::class
        ]);

        $bookRepository = static::getContainer()->get(BookRepository::class);
        $books = $bookRepository->findAll();
        $book = $books[0];

        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/api/books/' . $book->getId());
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertEquals($book->getId(), json_decode($response->getContent())->id); // response data is correct
        $this->assertEquals($book->getName(), json_decode($response->getContent())->name); // response data is correct
        $this->assertSameSize($book->getAuthors(), json_decode($response->getContent())->authors); // response data is correct
    }

    /**
     * GET PUT /api/books/{id}
     *
     * @return void
     */
    public function testUpdate(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            BookFixtures::class
        ]);

        $bookRepository = static::getContainer()->get(BookRepository::class);
        $books = $bookRepository->findAll();
        $book = $books[0];

        // get data from api
        $this->client->loginUser($this->testUser);
        $data = array(
            'name' => 'Book change name',
            'authors' => [1]
        );
        sleep(5);
        $this->client->request('PUT', '/api/books/' . $book->getId(), $data);
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertEquals($book->getId(), json_decode($response->getContent())->id); // response data is correct
        $this->assertEquals($data['name'], json_decode($response->getContent())->name); // response data is correct
        $this->assertSameSize($data['authors'], json_decode($response->getContent())->authors); // response data is correct
    }

    /**
     * GET DELETE /api/books/{id}
     *
     * @return void
     */
    public function testDelete(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            BookFixtures::class
        ]);

        $bookRepository = static::getContainer()->get(BookRepository::class);
        $books = $bookRepository->findAll();
        $bookId = $books[0]->getId();

        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('DELETE', '/api/books/' . $bookId);
        $this->assertResponseIsSuccessful(); // send the request success

        $this->assertNull($bookRepository->find($bookId)); // record was deleted from database
    }
}
