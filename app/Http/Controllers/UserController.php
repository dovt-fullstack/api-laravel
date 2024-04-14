<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/users/{id}/role",
     *     summary="Update the role of the specified user",
     *     description="Update the role of the specified user.",
     *     operationId="updateRole",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to update role",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated role",
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User role updated successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     *
     * Update the role of the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|max:255',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->role = $request->input('role');
        $user->save();

        return response()->json(['message' => 'User role updated successfully']);
    }
    public function updateProfile(Request $request, $id) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $id,
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }
    public function resetPassword(Request $request)
    {
        // Validate username, email, and new password
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'new_password' => 'required|string|min:6', // Thêm validation cho mật khẩu mới
        ]);
        // Check if user exists
        $user = User::where('name', $request->name)
                    ->where('email', $request->email)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid name or email'], 404);
        }

        // Set new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Return success response
        return response()->json(['message' => 'Password reset successfully'], 200);
    }
}
