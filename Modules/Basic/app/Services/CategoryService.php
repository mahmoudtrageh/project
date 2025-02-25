<?php

namespace Modules\Basic\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Modules\Basic\Models\Category;
use Modules\Basic\Repositories\CategoryRepositoryInterface;

class CategoryService
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    private FileUploadService $fileUploadService;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, FileUploadService $fileUploadService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

    /**
     * Get categories with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedCategories(int $perPage = 15): LengthAwarePaginator
    {
        return $this->categoryRepository->paginate($perPage);
    }

    /**
     * Get a category by its ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * Get a category by its slug.
     *
     * @param string $slug
     * @return Category|null
     */
    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->categoryRepository->findBySlug($slug);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        if (isset($data['image'])) {
            $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'categories');
        }

        // Ensure slug is valid
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Set defaults if not provided
        $data['is_active'] = $data['is_active'] ?? true;
        $data['order'] = $data['order'] ?? 0;

        return $this->categoryRepository->create($data);
    }

    /**
     * Update a category.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCategory(int $id, array $data): bool
    {
        $category = $this->categoryRepository->find($id);
        
        if (isset($data['image'])) {
            if ($category->image) {
                $this->fileUploadService->deleteFile($category->image);
            }
            $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'categories');
        }

        // Ensure slug is valid if provided
        if (isset($data['slug']) && !empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        }

        return $this->categoryRepository->update($id, $data);
    }

    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        $category = $this->categoryRepository->find($id);
        
        if ($category->image) {
            $this->fileUploadService->deleteFile($category->image);
        }

        return $this->categoryRepository->delete($id);
    }

    /**
     * Get active categories.
     *
     * @return Collection
     */
    public function getActiveCategories(): Collection
    {
        return $this->categoryRepository->getActive();
    }

    /**
     * Get root categories.
     *
     * @return Collection
     */
    public function getRootCategories(): Collection
    {
        return $this->categoryRepository->getRootCategories();
    }

    /**
     * Get child categories by parent ID.
     *
     * @param int $parentId
     * @return Collection
     */
    public function getChildCategories(int $parentId): Collection
    {
        return $this->categoryRepository->getChildCategories($parentId);
    }

    /**
     * Get categories as a hierarchical tree.
     *
     * @return Collection
     */
    public function getCategoryTree(): Collection
    {
        return $this->categoryRepository->getCategoryTree();
    }

    /**
     * Validate if a category can be deleted safely.
     *
     * @param int $id
     * @return bool
     */
    public function canDeleteCategory(int $id): bool
    {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            return false;
        }
        
        // Check if category has children
        return $category->children->isEmpty();
    }

    /**
     * Move a category to a new parent.
     *
     * @param int $categoryId
     * @param int|null $newParentId
     * @return bool
     */
    public function moveCategory(int $categoryId, ?int $newParentId): bool
    {
        // Validate that we're not creating a circular reference
        if ($newParentId !== null) {
            $parent = $this->getCategoryById($newParentId);
            if (!$parent) {
                return false;
            }

            // Check if the new parent is not a child of the category being moved
            $currentCategory = $this->getCategoryById($categoryId);
            $childIds = $this->getDescendantIds($currentCategory);
            
            if (in_array($newParentId, $childIds)) {
                return false;
            }
        }

        return $this->categoryRepository->update($categoryId, ['parent_id' => $newParentId]);
    }

    /**
     * Get all descendant IDs for a category.
     *
     * @param Category $category
     * @return array
     */
    protected function getDescendantIds(Category $category): array
    {
        $ids = [];

        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }

        return $ids;
    }
}