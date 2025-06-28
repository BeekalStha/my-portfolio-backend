<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutMe\StoreAboutMeRequest;
use App\Http\Requests\AboutMe\UpdateAboutMeRequest;
use App\Http\Resources\AboutMeResource;
use App\Models\AboutMe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AboutMeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aboutMe = AboutMe::with('user')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'About Me entries retrieved successfully',
            'data' => AboutMeResource::collection($aboutMe),
            'meta' => [
                'current_page' => $aboutMe->currentPage(),
                'last_page' => $aboutMe->lastPage(),
                'per_page' => $aboutMe->perPage(),
                'total' => $aboutMe->total(),
                'from' => $aboutMe->firstItem(),
                'to' => $aboutMe->lastItem(),
            ],
            'links' => [
                'first' => $aboutMe->url(1),
                'last' => $aboutMe->url($aboutMe->lastPage()),
                'prev' => $aboutMe->previousPageUrl(),
                'next' => $aboutMe->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAboutMeRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Auto-generate slug if not provided
            if (empty($data['slug'])) {
                $base = $data['name'] ?? $data['title'] ?? 'about-me';
                $data['slug'] = Str::slug($base);
            }

            // Handle file upload
            if ($request->hasFile('profile_picture')) {
                $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            $aboutMe = AboutMe::create($data);

            DB::commit();

            return (new AboutMeResource($aboutMe))
                ->additional([
                    'success' => true,
                    'message' => 'About Me entry created successfully',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error creating About Me entry: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AboutMe $aboutMe)
    {
        return (new AboutMeResource($aboutMe->load('user')))
            ->additional([
                'success' => true,
                'message' => 'About Me entry retrieved successfully',
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAboutMeRequest $request, AboutMe $aboutMe)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Auto-generate slug if not provided in update
            if (empty($data['slug'])) {
                $base = $data['name'] ?? $data['title'] ?? $aboutMe->name ?? 'about-me';
                $data['slug'] = Str::slug($base);
            }

            // Handle profile picture update
            if ($request->hasFile('profile_picture')) {
                if ($aboutMe->profile_picture && Storage::disk('public')->exists($aboutMe->profile_picture)) {
                    Storage::disk('public')->delete($aboutMe->profile_picture);
                }

                $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            $aboutMe->update($data);

            DB::commit();

            return (new AboutMeResource($aboutMe))
                ->additional([
                    'success' => true,
                    'message' => 'About Me entry updated successfully',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating About Me entry: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutMe $aboutMe)
    {
        try {
            if ($aboutMe->profile_picture && Storage::disk('public')->exists($aboutMe->profile_picture)) {
                Storage::disk('public')->delete($aboutMe->profile_picture);
            }

            $aboutMe->delete();

            return response()->json([
                'success' => true,
                'message' => 'About Me entry deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting About Me entry: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Not used in API context.
     */
    public function create()
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not available for API',
        ], 405);
    }

    public function edit(AboutMe $aboutMe)
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not available for API',
        ], 405);
    }
}
