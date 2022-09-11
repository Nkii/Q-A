<?php
/**
 * Question service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class QuestionService.
 */
abstract class QuestionService implements QuestionServiceInterface
{
    /**
     * Question repository.
     */
    private QuestionRepository $questionRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Category service.
     */
    private CategoryService $categoryService;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService Category service
     * @param PaginatorInterface       $paginator       Paginator
     * @param QuestionRepository       $questionRepository  Question Repository
     */
    public function __construct(
        CategoryServiceInterface $categoryService,
        PaginatorInterface $paginator,
        QuestionRepository $questionRepository
    ) {
        $this->categoryService = $categoryService;
        $this->paginator = $paginator;
        $this->questionRepository = $questionRepository;
    }


    /**
     * Create paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->questionRepository->queryAll($filters),
            $page,
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
    /**
     * Prepare filters for the recipes list.
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
     * Get paginated list for category.
     *
     * @param int      $page
     * @param Category $category
     *
     * @return PaginationInterface
     */
    public function queryByCategory(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->questionRepository->queryByCategory($category),
            $page,
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Question $question Question entity
     */
    public function save(Question $question): void
    {
        $this->questionRepository->save($question);
    }

    /**
     * Delete entity.
     *
     * @param Question $question Question entity
     */
    public function delete(Question $question): void
    {
        $this->questionRepository->delete($question);
    }
}