<?php

namespace App\Tests;

use App\DataFixtures\AuthorFixtures;
use App\DataFixtures\BookFixtures;
use App\DataFixtures\OrderFixtures;
use App\Repository\OrderRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class OrderControllerTest extends ApiTestCase
{
    /**
     * Test GET /api/orders
     *
     * @return void
     */
    public function testList(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            OrderFixtures::class
        ]);

        // get data from api
        $this->client->request('GET', '/api/orders');
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();

        // get data from query
        $orderRepository = static::getContainer()->get(OrderRepository::class);
        $orders = $orderRepository->findAll();
        $this->assertSameSize($orders, json_decode($response->getContent())); // check response size
    }

    /**
     * Test POST /api/orders
     *
     * @return void
     */
    public function testCreate(): void
    {
        // create Author test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            BookFixtures::class
        ]);

        $data = array(
            'phone' => '0352874392',
            'address' => 'Address creation',
            'status' => 2,
            'books' => [1, 2, 3]

        );
        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('POST', '/api/orders', $data);
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertNotNull(json_decode($response->getContent())->id); // response data is correct

        // get data from query
        $orderRepository = static::getContainer()->get(OrderRepository::class);
        $order = $orderRepository->find(json_decode($response->getContent())->id);
        $this->assertNotNull($order); // record exist on database
        $this->assertEquals($data['address'], $order->getAddress()); // record exist on database
        $this->assertEquals($data['status'], $order->getStatus()); // record exist on database
        $this->assertCount(3, $order->getBooks()); // record exist on database
    }

    /**
     * GET POST /api/orders/{id}
     *
     * @return void
     */
    public function testShow(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            OrderFixtures::class
        ]);

        $orderRepository = static::getContainer()->get(OrderRepository::class);
        $orders = $orderRepository->findAll();
        $order = $orders[0];

        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/api/orders/' . $order->getId());
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertEquals($order->getId(), json_decode($response->getContent())->id); // response data is correct
        $this->assertEquals($order->getPhone(), json_decode($response->getContent())->phone); // response data is correct
        $this->assertEquals($order->getAddress(), json_decode($response->getContent())->address); // response data is correct
        $this->assertEquals($order->getStatus(), json_decode($response->getContent())->status); // response data is correct
        $this->assertSameSize($order->getBooks(), json_decode($response->getContent())->books); // response data is correct
    }

    /**
     * GET PUT /api/orders/{id}
     *
     * @return void
     */
    public function testUpdate(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            OrderFixtures::class
        ]);

        $orderRepository = static::getContainer()->get(OrderRepository::class);
        $orders = $orderRepository->findAll();
        $order = $orders[0];

        // get data from api
        $this->client->loginUser($this->testUser);
        $data = array(
            'phone' => '0352874395',
            'address' => 'Address creation changed',
            'status' => 0,
            'books' => [1]

        );
        $this->client->request('PUT', '/api/orders/' . $order->getId(), $data);
        $this->assertResponseIsSuccessful(); // send the request success
        $response = $this->client->getResponse();
        $this->assertNotNull($order); // record exist on database
        $this->assertEquals($order->getId(), json_decode($response->getContent())->id); // response data is correct
        $this->assertEquals($data['status'], json_decode($response->getContent())->status); // response data is correct
        $this->assertEquals($data['address'], json_decode($response->getContent())->address); // response data is correct
        $this->assertSameSize($data['books'], json_decode($response->getContent())->books); // response data is correct
    }

    /**
     * GET DELETE /api/orders/{id}
     *
     * @return void
     */
    public function testDelete(): void
    {
        // create test data
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            OrderFixtures::class
        ]);

        $orderRepository = static::getContainer()->get(OrderRepository::class);
        $orders = $orderRepository->findAll();
        $orderId = $orders[0]->getId();

        // get data from api
        $this->client->loginUser($this->testUser);
        $this->client->request('DELETE', '/api/orders/' . $orderId);
        $this->assertResponseIsSuccessful(); // send the request success

        $this->assertNull($orderRepository->find($orderId)); // record was deleted from database
    }
}
