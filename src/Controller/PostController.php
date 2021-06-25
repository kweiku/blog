<?php
/**
 * Post controller.
 */

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\PostService;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route("/post")
 *
 */
class PostController extends AbstractController
{
    /**
     * Post service.
     *
     * @var \App\Service\PostService
     */
    private $postService;

    /**
     * PostController constructor.
     *
     * @param \App\Service\PostService $postService Post service
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="post_index",
     * )
     */
    public function index(Request $request): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');

        $pagination = $this->postService->createPaginatedList(
            $request->query->getInt('page', 1),
            //$this->getUser(),
            $filters
        );

        return $this->render(
            'post/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Post $post Post entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="post_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted(
     *     "VIEW",
     *     subject="post",
     * )
     */
    public function show(Post $post): Response
    {
        return $this->render(
            'post/show.html.twig',
            ['post' => $post]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="post_create",
     * )
     */
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $this->postService->save($post);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Post $post Post entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="post_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="post",
     * )
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Post $post Post entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="post_delete",
     * )
     *
     * @IsGranted(
     *     "DELETE",
     *     subject="post",
     * )
     */
    public function delete(Request $request, Post $post): Response
    {
        $form = $this->createForm(FormType::class, $post, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->delete($post);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/delete.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }

    /**
     * Create comment action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\CommentRepository $commentRepository Comment repository
     * @param \App\Entity\Post $post Post entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/comment",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="create",
     * )
     */
    public function createComment(Request $request, CommentRepository $commentRepository, Post $post): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($this->getId());
            $comment->setCreatedAt(new \DateTime());
            $commentRepository->save($comment);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('post_show');
        }

        return $this->render(
            'post/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
