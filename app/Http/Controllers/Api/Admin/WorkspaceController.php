<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkspaceResource;

class WorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = Workspace::orderBy('name')->get();
        return $this->returnSuccess(200, WorkspaceResource::collection($workspaces));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $workspace = Workspace::create($validated);
        return $this->returnSuccess(201, new WorkspaceResource($workspace));
    }

    public function show(Workspace $workspace)
    {
        $workspace->load('schedules');
        return $this->returnSuccess(200, new WorkspaceResource($workspace));
    }

    public function update(Request $request, Workspace $workspace)
    {
        $validated = $request->validate([
            'name' => 'string|max:50',
            'is_active' => 'boolean',
        ]);

        $workspace->update($validated);
        return $this->returnSuccess(200, new WorkspaceResource($workspace));
    }

    public function destroy(Workspace $workspace)
    {
        $workspace->delete();
        return $this->returnSuccess(200, ['message' => 'Workspace deleted']);
    }
}
