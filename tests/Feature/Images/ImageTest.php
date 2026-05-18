<?php

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\Concerns\CreatesUsers;

uses(
    LazilyRefreshDatabase::class,
    CreatesUsers::class,
);

beforeEach(function () {
    setPermissionsTeamId(1);

    Role::firstOrCreate(['name' => 'admin']);
});

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

describe('index', function () {
    test('can list images', function () {
        Image::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('images.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list images', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('images.index'));

        $response->assertForbidden();
    });

    test('guest cannot list images', function () {
        auth()->logout();
        
        $response = $this->getJson(route('images.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a image', function () {
        $user = adminUser();

        $data = [
            'title' => 'Technology',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment(['title' => 'Technology']);

        $this->assertDatabaseHas('images', [
            'title' => 'Technology',
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    });

    test('unauthorized user cannot create image', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('images.store'), [
                'title' => 'Technology',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a image', function () {
        $user = adminUser();
        $image = Image::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('images.show', $image));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $image->id,
                'title' => $image->title,
            ]);
    });

    test('returns 404 for non-existent image', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('images.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view image', function () {
        $unauthorizedUser = User::factory()->create();
        $image = Image::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('images.show', $image));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a image', function () {
        $user = adminUser();
        $image = Image::factory()->create(['title' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('images.update', $image),
                ['title' => 'New Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['title' => 'New Name']);

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'title' => 'New Name',
        ]);
    });

    test('can update a image via patch', function () {
        $user = adminUser();
        $image = Image::factory()->create(['title' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('images.patch', $image),
                ['title' => 'Patched Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['title' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $image = Image::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('images.update', $image),
                ['title' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    });

    test('unauthorized user cannot update image', function () {
        $unauthorizedUser = User::factory()->create();
        $image = Image::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('images.update', $image),
                ['title' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a image', function () {
        $user = adminUser();
        $image = Image::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('images.destroy', $image));

        $response->assertNoContent();

        $this->assertSoftDeleted('images', [
            'id' => $image->id,
        ]);
    });

    test('unauthorized user cannot delete image', function () {
        $unauthorizedUser = User::factory()->create();
        $image = Image::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('images.destroy', $image));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted image', function () {
        $user = adminUser();
        $image = Image::factory()->create();
        $image->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.restore', $image->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $image->id]);

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted image', function () {
        $user = adminUser();
        $image = Image::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.restore', $image->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent image', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore image', function () {
        $unauthorizedUser = User::factory()->create();
        $image = Image::factory()->create();
        $image->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('images.restore', $image->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a image', function () {
        $user = adminUser();
        $image = Image::factory()->create();
        $image->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('images.force-delete', $image->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('images', [
            'id' => $image->id,
        ]);
    });

    test('can force delete a non-soft-deleted image', function () {
        $user = adminUser();
        $image = Image::factory()->create();
        $image->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('images.force-delete', $image->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('images', [
            'id' => $image->id,
        ]);
    });

    test('unauthorized user cannot force delete image', function () {
        $unauthorizedUser = User::factory()->create();
        $image = Image::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('images.force-delete', $image->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple images', function () {
        $user = adminUser();
        $images = Image::factory()->count(3)->create();
        $ids = $images->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Image deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('images', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple image', function () {
        $user = adminUser();
        $images = Image::factory()->count(3)->create();
        $images->each->delete();
        $ids = $images->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Image restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('images', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('images.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
