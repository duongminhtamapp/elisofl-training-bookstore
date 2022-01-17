<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class ApiTestCase extends WebTestCase
{
    /* @var AbstractDatabaseTool */
    protected $databaseTool;

    protected $testUser;
    protected $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            UserFixtures::class
        ]);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->testUser = $userRepository->findOneByEmail('unit.test@gmail.com');
        $this->client->loginUser($this->testUser);

    }
}