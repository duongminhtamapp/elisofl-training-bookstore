<?php

namespace App\Controller;


use App\Entity\Order;
use App\Entity\Customer;
use App\Entity\User;
use App\Form\Type\OrderType;
use App\Helper\NumverifyApi;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends AbstractApiController
{
    /**
     * Get all or search order with condition
     *
     * @param Request $request
     * @return Response
     */
    public function list(Request $request, ManagerRegistry $doctrine): Response
    {
        $orders = $doctrine->getManager()->getRepository(Order::class)->findAll();
        return $this->respond($orders);
    }

    /**
     * Create a Order
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create(Request $request, ManagerRegistry $doctrine, LoggerInterface $logger): Response
    {
        // validation
        $order = new Order();
        $form = $this->buildForm(OrderType::class, $order);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form);
        }

        // format phone number
        $numverifyApi = new NumverifyApi($request->get('phone'));
        $phone = $numverifyApi->getInternationalPhone();
        if ($phone) {
            $order->setPhone($phone);
        }

        // save order
        $doctrine->getManager()->persist($order);
        $doctrine->getManager()->flush();
        $logger->error('Test Log');

        return $this->respond($order, Response::HTTP_CREATED);
    }

    /**
     * Get a Order
     *
     * @param int $id
     * @param OrderRepository $orderRepository
     * @return Response
     */
    public function show(int $id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find($id);

        // check order is exist
        if (!$order) {
            throw new NotFoundHttpException('Order is not found');
        }

        return $this->respond($order);
    }

    /**
     * Update a Order
     *
     * @param Request $request
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function update(Request $request, int $id, OrderRepository $orderRepository, ManagerRegistry $doctrine): Response
    {
        // check order is exist
        $order = $orderRepository->find($id);
        if (!$order) {
            throw new NotFoundHttpException('Order is not found');
        }

        // validation
        $form = $this->buildForm(OrderType::class, $order, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form);
        }

        $order = $form->getData();

        // format phone number
        sleep(3);
        $numverifyApi = new NumverifyApi($request->get('phone'));
        $phone = $numverifyApi->getInternationalPhone();
        if ($phone) {
            $order->setPhone($phone);
        }

        // save order
        $doctrine->getManager()->persist($order);
        $doctrine->getManager()->flush();

        return $this->respond($order);
    }

    /**
     * Delete a Order
     *
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function delete(int $id, OrderRepository $orderRepository, ManagerRegistry $doctrine): Response
    {
        // check order is exist
        $order = $orderRepository->find($id);
        if (!$order) {
            throw new NotFoundHttpException('Order is not found');
        }

        // delete order
        $doctrine->getManager()->remove($order);
        $doctrine->getManager()->flush();

        return $this->respond('Order was deleted');
    }
}