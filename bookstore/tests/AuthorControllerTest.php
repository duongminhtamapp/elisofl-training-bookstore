<?php

namespace App\Tests;

use App\DataFixtures\AuthorFixtures;
use App\Repository\AuthorRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class AuthorControllerTest extends ApiTestCase
{
    /**
     * Test GET /api/authors
     *
     * @return void
     */
    public function testList(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AuthorFixtures::class
        ]);

        // get data from api
        $this->client->request('GET', '/api/authors');
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();

        // get data from query
        $authorRepository = static::getContainer()->get(AuthorRepository::class);
        $authors = $authorRepository->findAll();
        $this->assertSameSize($authors, json_decode($response->getContent())); // check response size
    }

    /**
     * Test POST /api/authors
     *
     * @return void
     */
    public function testCreate(): void
    {
        $data = array(
            'name' => 'Author test'
        );
        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('POST', '/api/authors', $data);
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertNotNull(json_decode($response->getContent())->id); // response data is correct

        // get data from query
        $authorRepository = static::getContainer()->get(AuthorRepository::class);
        $author = $authorRepository->find(json_decode($response->getContent())->id);
        $this->assertNotNull($author); // record exist on database
        $this->assertEquals('Author test', $author->getName()); // record exist on database
    }

    /**
     * GET POST /api/authors/{id}
     *
     * @return void
     */
    public function testShow(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AuthorFixtures::class
        ]);

        $authorRepository = static::getContainer()->get(AuthorRepository::class);
        $authors = $authorRepository->findAll();
        $author = $authors[0];

        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/api/authors/' . $author->getId());
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertEquals($author->getId(), json_decode($response->getContent())->id); // response data is correct
        $this->assertEquals($author->getName(), json_decode($response->getContent())->name); // response data is correct
    }

    /**
     * GET PUT /api/authors/{id}
     *
     * @return void
     */
    public function testUpdate(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AuthorFixtures::class
        ]);

        $authorRepository = static::getContainer()->get(AuthorRepository::class);
        $authors = $authorRepository->findAll();
        $author = $authors[0];

        // get data from api
        $this->client->loginUser($this->testUser);
        $data = array(
            'name' => 'Author change name'
        );
        $this->client->request('PUT', '/api/authors/' . $author->getId(), $data);
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertEquals($author->getId(), json_decode($response->getContent())->id); // response data is correct
        $this->assertEquals($data['name'], json_decode($response->getContent())->name); // response data is correct
    }

    /**
     * GET DELETE /api/authors/{id}
     *
     * @return void
     */
    public function testDelete(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            AuthorFixtures::class
        ]);

        $authorRepository = static::getContainer()->get(AuthorRepository::class);
        $authors = $authorRepository->findAll();
        $authorId = $authors[0]->getId();

        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('DELETE', '/api/authors/' . $authorId);
        $this->assertResponseIsSuccessful(); // send the request success

        $this->assertNull($authorRepository->find($authorId)); // record was deleted from database
    }
}
