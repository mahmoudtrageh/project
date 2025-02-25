<?php

namespace Modules\Basic\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Modules\Basic\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var Category
     */
    protected $model;

    /**
     * CategoryRepository constructor.
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    /**
     * {@inheritdoc}
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->ordered()->get($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->ordered()->paginate($perPage, $columns);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id, array $columns = ['*']): ?Category
    {
        return $this->model->select($columns)->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findBySlug(string $slug, array $columns = ['*']): ?Category
    {
        return $this->model->select($columns)->where('slug', $slug)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Category
    {
        // Generate slug if not provided
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): bool
    {
        $category = $this->find($id);
        
        if (!$category) {
            return false;
        }

        // Generate slug if not provided but name changed
        if ((!isset($data['slug']) || empty($data['slug'])) && isset($data['name']) && $data['name'] !== $category->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $category->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $category = $this->find($id);
        
        if (!$category) {
            return false;
        }

        return (bool) $category->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function getActive(array $columns = ['*']): Collection
    {
        return $this->model->active()->ordered()->get($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function getRootCategories(array $columns = ['*']): Collection
    {
        return $this->model->root()->ordered()->get($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildCategories(int $parentId, array $columns = ['*']): Collection
    {
        return $this->model->where('parent_id', $parentId)->ordered()->get($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryTree(): Collection
    {
        return $this->model->root()->with('descendants')->ordered()->get();
    }
}