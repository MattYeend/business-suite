<?php

namespace Tests\Concerns;

use App\Models\User;
use Spatie\Permission\Models\Role;

trait CreatesUsers
{
}

function adminUser(): User
{
    $user = User::factory()->create([
        'is_admin' => true,
        'is_super_admin' => false,
        'is_user' => true,
    ]);

    $user->assignRole('admin');
    
    return $user;
}