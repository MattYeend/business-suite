<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
// use App\Http\Requests\StoreUserRequest;
// use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
// use App\Services\Users\UserLogService;
// use App\Services\Users\UserManagementService;
// use App\Services\Users\UserQueryService;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
// use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Empty
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // Add other validation rules as needed
        ]);

        // Generate a random password
        $plainPassword = Str::random(12);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($plainPassword),
        ]);

        // Dispatch the welcome email manually with the password
        Mail::to($user->email)
            ->queue(new WelcomeEmail($user, $plainPassword));

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully. Welcome email sent.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     // Empty
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     // Empty
    // }

    /**
     * Restore the specified resource from storage.
     */
    // public function restore(string $id)
    // {
    //     // Empty
    // }
}
