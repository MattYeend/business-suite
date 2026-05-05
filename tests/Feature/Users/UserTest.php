<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Spatie\Permission\Models\Role;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    setPermissionsTeamId(1);

    Role::firstOrCreate(['name' => 'admin']);
});

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
test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

describe('index', function () {
    test('can list users', function () {
        User::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('users.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list users', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('users.index'));

        $response->assertForbidden();
    });

    test('guest cannot list users', function () {
        auth()->logout();
        
        $response = $this->getJson(route('users.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a user', function () {
        $user = adminUser();
        
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'John Doe', 'email' => 'john.doe@example.com']);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    });

    test('validation fails with duplicate email', function () {
        $user = adminUser();
        $existingUser = User::factory()->create(['email' => 'duplicate@example.com']);
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.store'), [
                'name' => 'New User',
                'email' => 'duplicate@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    });

    test('unauthorized user cannot create user', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('users.store'), [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => 'password123',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a user', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->getJson(route('users.show', $targetUser));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $targetUser->id,
                'name' => $targetUser->name,
                'email' => $targetUser->email,
            ]);
    });

    test('returns 404 for non-existent user', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('users.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view user', function () {
        $unauthorizedUser = User::factory()->create();
        $targetUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('users.show', $targetUser));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a user', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->putJson(
                route('users.update', $targetUser),
                ['name' => 'New Name', 'email' => $targetUser->email]
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'New Name',
        ]);
    });

    test('can update a user via patch', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->patchJson(
                route('users.patch', $targetUser),
                ['name' => 'Patched Name', 'email' => $targetUser->email]
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->putJson(
                route('users.update', $targetUser),
                ['name' => '', 'email' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email']);
    });

    test('validation fails with duplicate email', function () {
        $adminUserInstance = adminUser();
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);
        $targetUser = User::factory()->create(['email' => 'target@example.com']);

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->putJson(
                route('users.update', $targetUser),
                ['name' => 'Updated Name', 'email' => 'existing@example.com']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    });

    test('unauthorized user cannot update user', function () {
        $unauthorizedUser = User::factory()->create();
        $targetUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('users.update', $targetUser),
                ['name' => 'Updated', 'email' => 'updated@example.com']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a user', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->deleteJson(route('users.destroy', $targetUser));

        $response->assertNoContent();

        $this->assertSoftDeleted('users', [
            'id' => $targetUser->id,
        ]);
    });

    test('unauthorized user cannot delete user', function () {
        $unauthorizedUser = User::factory()->create();
        $targetUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('users.destroy', $targetUser));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted user', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create();
        $targetUser->delete();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->postJson(route('users.restore', $targetUser->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $targetUser->id]);

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted user', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->postJson(route('users.restore', $targetUser->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent user', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore user', function () {
        $unauthorizedUser = User::factory()->create();
        $targetUser = User::factory()->create();
        $targetUser->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('users.restore', $targetUser->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a user', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create();
        $targetUser->delete();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->deleteJson(route('users.force-delete', $targetUser->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id,
        ]);
    });

    test('can force delete a non-soft-deleted user', function () {
        $adminUserInstance = adminUser();
        $targetUser = User::factory()->create();
        $targetUser->delete();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->deleteJson(route('users.force-delete', $targetUser->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id,
        ]);
    });

    test('unauthorized user cannot force delete user', function () {
        $unauthorizedUser = User::factory()->create();
        $targetUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('users.force-delete', $targetUser->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple users', function () {
        $adminUserInstance = adminUser();
        $users = User::factory()->count(3)->create();
        $ids = $users->pluck('id')->toArray();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->postJson(route('users.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Users deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('users', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple users', function () {
        $adminUserInstance = adminUser();
        $users = User::factory()->count(3)->create();
        $users->each->delete();
        $ids = $users->pluck('id')->toArray();

        $response = $this->actingAs($adminUserInstance, 'sanctum')
            ->postJson(route('users.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Users restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('users', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('users.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
