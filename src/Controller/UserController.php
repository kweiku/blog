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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $log = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('user/show.html.twig', ['users' => $user]);
        }
        else {
            return $this->render('user/show.html.twig', ['users' => $log]);
        }
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param User $user User
     * @param UserRepository $userRepository User Repository
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
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserDataType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );


            $userRepository->save($user);
            $this->addFlash('success', 'message_updated_successfully');
            $this->redirectToRoute('post_index');
            }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'users' => $user,
        ]);

    }
}
