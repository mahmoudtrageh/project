<?php

namespace Modules\Cms\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Cms\Http\Requests\Page\PageRequest;
use Modules\Cms\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('cms::pages.index', compact('pages'));
    }

    public function create()
    {
        return view('cms::pages.create');
    }

    public function store(PageRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('pages', 'public');
            }

            if ($data['status'] === 'published' && !isset($data['published_at'])) {
                $data['published_at'] = now();
            }

            Page::create($data);

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating page: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Page $page)
    {
        return view('cms::pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                if ($page->image && Storage::disk('public')->exists($page->image)) {
                    Storage::disk('public')->delete($page->image);
                }
                $data['image'] = $request->file('image')->store('pages', 'public');
            }

            if ($data['status'] === 'published' && !$page->published_at) {
                $data['published_at'] = now();
            }

            $page->update($data);

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating page: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Page $page)
    {
        try {
            if ($page->image && Storage::disk('public')->exists($page->image)) {
                Storage::disk('public')->delete($page->image);
            }

            $page->delete();

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting page: ' . $e->getMessage());
        }
    }

    public function updateOrder(Request $request)
    {
        $pages = $request->get('pages', []);
        
        foreach($pages as $order => $pageId) {
            Page::where('id', $pageId)->update(['order' => $order]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
