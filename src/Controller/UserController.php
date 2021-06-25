<?php

/**
 * Users controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserDataType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Show action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="users_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(User $user): Response
    {
        $this->getUser();
            return $this->render('user/show.html.twig', ['users' => $user]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="users_edit",
     * )
     *
     *@IsGranted(
     *     "EDIT",
     *     subject="user",
     * )
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $this->getUser();
        $form = $this->createForm(UserDataType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $userRepository->save($user, $newPassword);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('post_index');

        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'users' => $user,
        ]);
    }
}
