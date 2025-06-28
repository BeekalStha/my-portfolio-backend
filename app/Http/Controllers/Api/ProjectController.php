<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::with('user')->latest()->paginate(10);
            
            return response()->json([
                'success' => true,
                'message' => 'Projects retrieved successfully',
                'data' => ProjectResource::collection($projects),
                'meta' => $this->getPaginationMeta($projects),
                'links' => $this->getPaginationLinks($projects),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving projects: ' . $e->getMessage());
        }
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['files'] = $this->handleFileUploads($request);
            
            $project = Project::create($validated);
            
            return response()->json([
                'success' => true,
                'data' => new ProjectResource($project),
                'message' => 'Project created successfully',
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Error creating project: ' . $e->getMessage());
        }
    }

    public function show(Project $project)
    {
        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
            'message' => 'Project retrieved successfully',
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        try {
            $validated = $request->validated();
            $currentFiles = $project->files ?? [];
            
            // Handle new files
            if ($request->hasFile('files')) {
                $currentFiles = array_merge($currentFiles, $this->handleFileUploads($request));
            }
            
            // Handle deleted files
            if ($request->has('deleted_files')) {
                $currentFiles = $this->handleFileDeletions($currentFiles, $request->deleted_files);
            }
            
            $validated['files'] = $currentFiles;
            $project->update($validated);
            
            return response()->json([
                'success' => true,
                'data' => new ProjectResource($project),
                'message' => 'Project updated successfully',
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Error updating project: ' . $e->getMessage());
        }
    }

    public function destroy(Project $project)
    {
        try {
            $this->deleteAllProjectFiles($project);
            $project->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully',
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Error deleting project: ' . $e->getMessage());
        }
    }

    // Helper Methods
    protected function handleFileUploads($request): array
    {
        $uploadedFiles = [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('projects', 'public');
                $type = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image';
                
                $uploadedFiles[] = [
                    'path' => $path,
                    'type' => $type,
                ];
            }
        }
        
        return $uploadedFiles;
    }

    protected function handleFileDeletions(array $currentFiles, array $filesToDelete): array
    {
        return array_filter($currentFiles, function ($file) use ($filesToDelete) {
            if (in_array($file['path'], $filesToDelete)) {
                Storage::disk('public')->delete($file['path']);
                return false;
            }
            return true;
        });
    }

    protected function deleteAllProjectFiles(Project $project): void
    {
        if (empty($project->files)) {
            return;
        }

        foreach ($project->files as $file) {
            Storage::disk('public')->delete($file['path']);
        }
    }

    protected function getPaginationMeta($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }

    protected function getPaginationLinks($paginator): array
    {
        return [
            'first' => $paginator->url(1),
            'last' => $paginator->url($paginator->lastPage()),
            'prev' => $paginator->previousPageUrl(),
            'next' => $paginator->nextPageUrl(),
        ];
    }

    protected function errorResponse(string $message, int $status = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}