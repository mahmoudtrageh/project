<?php

namespace Modules\Basic\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Basic\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Basic\Http\Requests\Category\CategoryRequest;

class CategoryController extends Controller
{
 /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;      
    }

    /**
     * Display a listing of the categories.
     *
     * @return View
     */
    public function index(): View
    {
        $categories = $this->categoryService->getPaginatedCategories();
        
        return view('basic::categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return View
     */
    public function create(): View
    {
        $categories = $this->categoryService->getAllCategories();
        
        return view('basic::categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param CategoryRequest $request
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = $this->categoryService->createCategory($request->validated());
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $category = $this->categoryService->getCategoryById($id);
        
        if (!$category) {
            abort(404);
        }
        
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $category = $this->categoryService->getCategoryById($id);
        
        if (!$category) {
            abort(404);
        }
        
        $categories = $this->categoryService->getAllCategories()
            ->filter(function ($item) use ($id) {
                // Filter out the current category and its descendants
                return $item->id != $id;
            });
        
        return view('basic::categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param CategoryRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(CategoryRequest $request, int $id): RedirectResponse
    {
        $result = $this->categoryService->updateCategory($id, $request->validated());
        
        if (!$result) {
            return back()->with('error', 'Category not found.');
        }
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // Check if the category can be safely deleted
        if (!$this->categoryService->canDeleteCategory($id)) {
            return back()->with('error', 'This category cannot be deleted because it has child categories.');
        }
        
        $result = $this->categoryService->deleteCategory($id);
        
        if (!$result) {
            return back()->with('error', 'Category not found.');
        }
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Get category tree (for API).
     *
     * @return JsonResponse
     */
    public function getTree(): JsonResponse
    {
        $tree = $this->categoryService->getCategoryTree();
        
        return response()->json([
            'success' => true,
            'data' => $tree
        ]);
    }

    /**
     * Move a category to a new parent.
     *
     * @param int $id
     * @param int|null $parentId
     * @return JsonResponse
     */
    public function move(int $id, ?int $parentId = null): JsonResponse
    {
        $success = $this->categoryService->moveCategory($id, $parentId);
        
        return response()->json([
            'success' => $success,
            'message' => $success 
                ? 'Category moved successfully.' 
                : 'Failed to move category.'
        ]);
    }
}
