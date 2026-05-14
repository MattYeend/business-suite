<?php

use App\Models\Part;
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
    test('can list parts', function () {
        Part::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('parts.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list parts', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('parts.index'));

        $response->assertForbidden();
    });

    test('guest cannot list parts', function () {
        auth()->logout();
        
        $response = $this->getJson(route('parts.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a part', function () {
        $user = adminUser();

        $data = [
            'sku' => 'SKU-0123',
            'name' => 'Technology',
            'description' => 'New Part',
            'type' => 'raw_material',
            'price' => '12.99'
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment([
                'sku' => 'SKU-0123',
                'name' => 'Technology',
                'description' => 'New Part',
                'type' => 'raw_material',
                'price' => '12.99'
            ]);

        $this->assertDatabaseHas('parts', [
            'sku' => 'SKU-0123',
            'name' => 'Technology',
            'description' => 'New Part',
            'type' => 'raw_material',
            'price' => '12.99'
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot create part', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('parts.store'), [
                'name' => 'Technology',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a part', function () {
        $user = adminUser();
        $part = Part::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('parts.show', $part));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $part->id,
                'name' => $part->name,
            ]);
    });

    test('returns 404 for non-existent part', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('parts.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view part', function () {
        $unauthorizedUser = User::factory()->create();
        $part = Part::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('parts.show', $part));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a part', function () {
        $user = adminUser();
        $part = Part::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('parts.update', $part),
                ['name' => 'New Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('parts', [
            'id' => $part->id,
            'name' => 'New Name',
        ]);
    });

    test('can update a part via patch', function () {
        $user = adminUser();
        $part = Part::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('parts.patch', $part),
                ['name' => 'Patched Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $part = Part::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('parts.update', $part),
                ['name' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot update part', function () {
        $unauthorizedUser = User::factory()->create();
        $part = Part::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('parts.update', $part),
                ['name' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a part', function () {
        $user = adminUser();
        $part = Part::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('parts.destroy', $part));

        $response->assertNoContent();

        $this->assertSoftDeleted('parts', [
            'id' => $part->id,
        ]);
    });

    test('unauthorized user cannot delete part', function () {
        $unauthorizedUser = User::factory()->create();
        $part = Part::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('parts.destroy', $part));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted part', function () {
        $user = adminUser();
        $part = Part::factory()->create();
        $part->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.restore', $part->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $part->id]);

        $this->assertDatabaseHas('parts', [
            'id' => $part->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted part', function () {
        $user = adminUser();
        $part = Part::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.restore', $part->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent part', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore part', function () {
        $unauthorizedUser = User::factory()->create();
        $part = Part::factory()->create();
        $part->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('parts.restore', $part->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a part', function () {
        $user = adminUser();
        $part = Part::factory()->create();
        $part->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('parts.force-delete', $part->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('parts', [
            'id' => $part->id,
        ]);
    });

    test('can force delete a non-soft-deleted part', function () {
        $user = adminUser();
        $part = Part::factory()->create();
        $part->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('parts.force-delete', $part->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('parts', [
            'id' => $part->id,
        ]);
    });

    test('unauthorized user cannot force delete part', function () {
        $unauthorizedUser = User::factory()->create();
        $part = Part::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('parts.force-delete', $part->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple parts', function () {
        $user = adminUser();
        $parts = Part::factory()->count(3)->create();
        $ids = $parts->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Part deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('parts', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple part', function () {
        $user = adminUser();
        $parts = Part::factory()->count(3)->create();
        $parts->each->delete();
        $ids = $parts->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Part restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('parts', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('parts.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
