<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Skill\StoreSkillRequest;
use App\Http\Requests\Skill\UpdateSkillRequest;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::latest()->paginate(10);
        return response()->json([
            'success' => true,
            'message' => 'Skills retrieved successfully',
            'data' => SkillResource::collection($skills),
            'meta' => [
                'current_page' => $skills->currentPage(),
                'last_page' => $skills->lastPage(),
                'per_page' => $skills->perPage(),
                'total' => $skills->total(),
                'from' => $skills->firstItem(),
                'to' => $skills->lastItem(),
            ],
            'links' => [
                'first' => $skills->url(1),
                'last' => $skills->url($skills->lastPage()),
                'prev' => $skills->previousPageUrl(),
                'next' => $skills->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSkillRequest $request)
    {
        $skill = Skill::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully',
            'data' => new SkillResource($skill),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        return response()->json([
            'success' => true,
            'message' => 'Skill retrieved successfully',
            'data' => new SkillResource($skill),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skill $skill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        $skill->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully',
            'data' => new SkillResource($skill),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        $skill->delete();
        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully',
        ]);
    }
}
