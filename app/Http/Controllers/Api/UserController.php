<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->query('role')) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        // Optionally add pagination
        $users = $query->paginate(10); // or use ->get() if not paginating

        return UserResource::collection($users);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|in:user,admin', // allow role, optional
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        // Assign provided role or default to 'user'
        $role = $validated['role'] ?? 'user';
        $user->assignRole($role);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
{
    $data = $request->only(['name', 'email', 'password', 'role']);

    if (isset($data['name'])) {
        $user->name = $data['name'];
    }

    if (isset($data['email'])) {
        $user->email = $data['email'];
    }

    if (isset($data['password'])) {
        $user->password = Hash::make($data['password']);
    }

    if (isset($data['role'])) {
        $user->syncRoles([$data['role']]);
    }

    $user->save();

    return response()->json(['message' => 'User updated successfully']);
}


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
