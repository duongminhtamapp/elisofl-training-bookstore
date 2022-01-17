<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractApiController
{
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->buildForm(UserType::class, $user);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }


        // encode the plain password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();
        // do anything else you need here, like send an email

        return $this->respond('You have Successfully Registered', Response::HTTP_CREATED);
    }
}
