<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    public function index()
    {
        try {
            Gate::authorize('viewAny', User::class);

            $users = User::where('is_delete', false)->get();

            return response()->json([
                'message' => 'Users retrieved successfully',
                'users'   => $users,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view users',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving users',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize('create', User::class);

            $fields = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'role'     => 'required|in:admin,staff,customer',
                'phone'    => 'nullable|string|max:20',
                'avatar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('avatar')) {
                $fields['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }
            $fields['created_user'] = $request->user()->id;

            $user = User::create($fields);

            return response()->json([
                'message' => 'User created successfully',
                'user'    => $user,
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to create user',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating user',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(User $user)
    {
        try {
            Gate::authorize('view', $user);

            return response()->json([
                'message' => 'User retrieved successfully',
                'user'    => $user,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view user',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving user',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            Gate::authorize('update', $user);

            $fields = $request->validate([
                'name'     => 'sometimes|required|string|max:255',
                'email'    => 'sometimes|required|email|unique:users,email,' . $user->id,
                'role'     => 'sometimes|required|in:admin,staff,customer',
                'phone'    => 'sometimes|nullable|string|max:20',
                'avatar'   => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
                'is_active'=> 'sometimes|boolean',
            ]);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $fields['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $fields['updated_by'] = $request->user()->id;

            $user->update($fields);

            return response()->json([
                'message' => 'User updated successfully',
                'user'    => $user->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update user',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating user',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            Gate::authorize('delete', $user);

            $user->update(['is_delete' => true]);
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully',
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete user',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                    'message' => 'Cannot delete user as it is currently in use',
                    'error'   => 'This user is linked to bookings or other records',
                ], 409);
            }
            return response()->json([
                'message' => 'An error occurred while deleting user',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function byRole(string $role)
    {
        try {
            Gate::authorize('viewAny', User::class);

            $users = User::where('role', $role)
                ->where('is_delete', false)
                ->get();

            return response()->json([
                'message' => ucfirst($role) . 's retrieved successfully',
                'users'   => $users,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view users',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving users',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function toggleStatus(Request $request, User $user)
    {
        try {
            Gate::authorize('update', $user);

            $user->update([
                'is_active'  => !$user->is_active,
                'updated_by' => $request->user()->id,
            ]);

            return response()->json([
                'message' => 'User status updated successfully',
                'user'    => $user->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update user status',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating user status',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}