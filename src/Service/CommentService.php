<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentService.
 */
class CommentService
{
    /**
     * Comment repository.
     *
     * @var \App\Repository\CommentRepository
     */
    private $commentRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * Post service.
     *
     * @var \App\Service\PostService
     */
    private $postService;

    /**
     * CommentService constructor.
     *
     * @param \App\Repository\CommentRepository $commentRepository Comment repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator Paginator
     */
    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator, PostService $postService)
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
        $this->postService = $postService;
    }

    /**
     * Prepare filters for the comment list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['post_id']) && is_numeric($filters['post_id'])) {
            $post = $this->postService->findOneById(
                $filters['post_id']
            );
            if (null !== $post) {
                $resultFilters['post'] = $post;
            }
        }

        return $resultFilters;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     * @param \Symfony\Component\Security\Core\User\UserInterface $user User entity
     * @param array $filters Filters array
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, array $filters): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->commentRepository->queryAll($filters),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save comment.
     *
     * @param \App\Entity\Comment $comment Comment entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Delete comment.
     *
     * @param \App\Entity\Comment $comment Comment entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}