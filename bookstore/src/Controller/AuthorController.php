<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\Type\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorController extends AbstractApiController
{
    /**
     * Get all or search author with condition
     *
     * @param Request $request
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function list(Request $request, AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();
        return $this->respond($authors);

    }

    /**
     * Create an Author
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        // validation
        $author = new Author();
        $form = $this->buildForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form);
        }

        // save author
        $doctrine->getManager()->persist($author);
        $doctrine->getManager()->flush();

        return $this->respond($author, Response::HTTP_CREATED);
    }

    /**
     * Get an Author
     *
     * @param int $id
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function show(int $id, AuthorRepository $authorRepository): Response
    {
        $author = $authorRepository->find($id);

        // check author is exist
        if (!$author) {
            throw new NotFoundHttpException('Author is not found');
        }

        return $this->respond($author);
    }

    /**
     * Update an Author
     *
     * @param Request $request
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function update(Request $request, int $id, AuthorRepository $authorRepository, ManagerRegistry $doctrine): Response
    {
        // check author is exist
        $author = $authorRepository->find($id);
        if (!$author) {
            throw new NotFoundHttpException('Author is not found');
        }

        // validation
        $form = $this->buildForm(AuthorType::class, $author, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form);
        }

        $author = $form->getData();

        // save author
        $doctrine->getManager()->persist($author);
        $doctrine->getManager()->flush();

        return $this->respond($author);
    }

    /**
     * Delete an Author
     *
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function delete(int $id, AuthorRepository $authorRepository, ManagerRegistry $doctrine): Response
    {
        // check author is exist
        $author = $authorRepository->find($id);
        if (!$author) {
            throw new NotFoundHttpException('Author is not found');
        }

        // delete author
        $doctrine->getManager()->remove($author);
        $doctrine->getManager()->flush();

        return $this->respond('Author was deleted');
    }
}