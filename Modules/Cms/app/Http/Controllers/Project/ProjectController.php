<?php

namespace Modules\Cms\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Cms\Http\Requests\Project\ProjectRequest;
use Modules\Cms\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(1);
        return view('cms::projects.index', compact('projects'));
    }

    public function create()
    {
        return view('cms::projects.create');
    }

    public function store(ProjectRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('projects', 'public');
            }
            
            $data['is_featured'] = $request->has('is_featured');

            Project::create($data);

            return redirect()->route('admin.projects.index')
                ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Error creating project: ' . $e->getMessage());
        }
    }

    public function edit(Project $project)
    {
        return view('cms::projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        try {
            $data = $request->validated();

            $data['is_featured'] = $request->has('is_featured');

            if ($request->hasFile('image')) {
                // Delete old image
                if ($project->image && Storage::disk('public')->exists($project->image)) {
                    Storage::disk('public')->delete($project->image);
                }
                $data['image'] = $request->file('image')->store('projects', 'public');
            }

            $project->update($data);

            return redirect()->route('admin.projects.index')
                ->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating project: ' . $e->getMessage());
        }
    }

    public function destroy(Project $project)
    {
        try {
            // Delete image
            if ($project->image && Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
            }

            $project->delete();

            return redirect()->route('admin.projects.index')
                ->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting project: ' . $e->getMessage());
        }
    }

    public function toggleFeatured(Project $project)
    {
        $project->update(['is_featured' => !$project->is_featured]);
        return back()->with('success', 'Project featured status updated.');
    }

    public function toggleStatus(Project $project)
    {
        $project->update([
            'status' => $project->status === 'published' ? 'draft' : 'published'
        ]);
        return back()->with('success', 'Project status updated.');
    }
}
