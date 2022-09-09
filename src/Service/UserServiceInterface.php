<?php

/**
 * User service interface.
 */

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save the entity.
     *
     * @param User $user
     *
     * @return void
     */
    public function save(User $user): void;

    /**
     * Delete the entity.
     *
     * @param User $user
     *
     * @return void
     */
    public function delete(User $user): void;
}