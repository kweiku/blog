<?php
/**
 * Post service.
 */

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PostService.
 */
class PostService
{
    /**
     * Post repository.
     *
     * @var \App\Repository\PostRepository
     */
    private $postRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private $categoryService;

    /**
     * PostService constructor.
     *
     * @param \App\Repository\PostRepository $postRepository Post repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator Paginator
     * @param \App\Service\CategoryService $categoryService Category service
     */
    public function __construct(PostRepository $postRepository, PaginatorInterface $paginator, CategoryService $categoryService)
    {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
    }

    /**
     * Prepare filters for the post list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $category = $this->categoryService->findOneById(
                $filters['category_id']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
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
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->postRepository->queryAll($filters),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save post.
     *
     * @param \App\Entity\Post $post Post entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Post $post): void
    {
        $this->postRepository->save($post);
    }

    /**
     * Delete post.
     *
     * @param \App\Entity\Post $post Post entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Post $post): void
    {
        $this->postRepository->delete($post);
    }

    /**
     * Find post by Id.
     *
     * @param int $id Post Id
     *
     * @return \App\Entity\Post|null Post entity
     */
    public function findOneById(int $id): ?Post
    {
        return $this->postRepository->findOneById($id);
    }
}