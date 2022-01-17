<?php

namespace App\Controller;


use App\Entity\Book;
use App\Entity\Customer;
use App\Form\Type\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends AbstractApiController
{
    /**
     * Get all or search book with condition
     *
     * @param Request $request
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function list(Request $request, BookRepository $bookRepository, ManagerRegistry $doctrine): Response
    {
        $filter = array();
        if($request->get('name')) {
            $filter['name'] = $request->get('name');
        }
        $books = $doctrine->getManager()->getRepository(Book::class)->findBy($filter);
        return $this->respond($books);

    }

    /**
     * Create a Book
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        // validation
        $book = new Book();
        $form = $this->buildForm(BookType::class, $book);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form);
        }

        // save book
        $doctrine->getManager()->persist($book);
        $doctrine->getManager()->flush();

        return $this->respond($book, Response::HTTP_CREATED);
    }

    /**
     * Get a Book
     *
     * @param int $id
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function show(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);

        // check book is exist
        if (!$book) {
            throw new NotFoundHttpException('Book is not found');
        }

        return $this->respond($book);
    }

    /**
     * Update a Book
     *
     * @param Request $request
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function update(Request $request, int $id, BookRepository $bookRepository, ManagerRegistry $doctrine): Response
    {
        // check book is exist
        $book = $bookRepository->find($id);
        if (!$book) {
            throw new NotFoundHttpException('Book is not found');
        }

        // validation
        $form = $this->buildForm(BookType::class, $book, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form);
        }

        $book = $form->getData();

        // save book
        $doctrine->getManager()->persist($book);
        $doctrine->getManager()->flush();

        return $this->respond($book);
    }

    /**
     * Delete a Book
     *
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function delete(int $id, BookRepository $bookRepository, ManagerRegistry $doctrine): Response
    {
        // check book is exist
        $book = $bookRepository->find($id);
        if (!$book) {
            throw new NotFoundHttpException('Book is not found');
        }

        // delete book
        $doctrine->getManager()->remove($book);
        $doctrine->getManager()->flush();

        return $this->respond('Book was deleted');
    }
}