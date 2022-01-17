<?php

namespace App\Tests;

use App\DataFixtures\AuthorFixtures;
use App\DataFixtures\BookFixtures;
use App\DataFixtures\OrderFixtures;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * Test POST /api/register
     *
     * @return void
     */
    public function testRegister(): void
    {
        $client = static::createClient();
        $data = array(
            'email' => 'test.user@gmail.com',
            'password' => '123456'
        );
        // get data from api
        $client->request('POST', '/api/register', $data);
        $this->assertResponseIsSuccessful(); // send the request success
    }
}
