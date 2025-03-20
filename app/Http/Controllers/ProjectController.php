<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('project.index', compact('projects'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',

        ]);

        $project = Project::create([
            'name' => $request->name,
        ]);
        return redirect()->route('projects.index');
    }
    public function update(Request $request, Project $project)
    {
        // Validate request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $project->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

}
