<?php

namespace Modules\Basic\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Basic\Models\Category;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get all categories with pagination.
     *
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Find a category by its ID.
     *
     * @param int $id
     * @param array $columns
     * @return Category|null
     */
    public function find(int $id, array $columns = ['*']): ?Category;

    /**
     * Find a category by its slug.
     *
     * @param string $slug
     * @param array $columns
     * @return Category|null
     */
    public function findBySlug(string $slug, array $columns = ['*']): ?Category;

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category;

    /**
     * Update a category.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get only active categories.
     *
     * @param array $columns
     * @return Collection
     */
    public function getActive(array $columns = ['*']): Collection;

    /**
     * Get only root categories.
     *
     * @param array $columns
     * @return Collection
     */
    public function getRootCategories(array $columns = ['*']): Collection;

    /**
     * Get child categories by parent ID.
     *
     * @param int $parentId
     * @param array $columns
     * @return Collection
     */
    public function getChildCategories(int $parentId, array $columns = ['*']): Collection;

    /**
     * Get categories as a tree structure.
     *
     * @return Collection
     */
    public function getCategoryTree(): Collection;
}