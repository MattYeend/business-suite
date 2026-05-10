<?php

use App\Models\Pipeline;
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
    test('can list companys', function () {
        Pipeline::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('pipelines.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list pipelines', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('pipelines.index'));

        $response->assertForbidden();
    });

    test('guest cannot list pipelines', function () {
        auth()->logout();
        
        $response = $this->getJson(route('pipelines.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a pipeline', function () {
        $user = adminUser();

        $data = [
            'name' => 'Technology',
            'entity_type' => 'task_entity'
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'Technology']);

        $this->assertDatabaseHas('pipelines', [
            'name' => 'Technology',
            'entity_type' => 'task_entity'
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot create pipeline', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('pipelines.store'), [
                'name' => 'Technology',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a pipeline', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('pipelines.show', $pipeline));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $pipeline->id,
                'name' => $pipeline->name,
            ]);
    });

    test('returns 404 for non-existent pipeline', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('pipelines.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view pipeline', function () {
        $unauthorizedUser = User::factory()->create();
        $pipeline = Pipeline::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('pipelines.show', $pipeline));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a pipeline', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('pipelines.update', $pipeline),
                ['name' => 'New Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('pipelines', [
            'id' => $pipeline->id,
            'name' => 'New Name',
        ]);
    });

    test('can update a pipeline via patch', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('pipelines.patch', $pipeline),
                ['name' => 'Patched Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('pipelines.update', $pipeline),
                ['name' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot update pipeline', function () {
        $unauthorizedUser = User::factory()->create();
        $pipeline = Pipeline::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('pipelines.update', $pipeline),
                ['name' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a pipeline', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('pipelines.destroy', $pipeline));

        $response->assertNoContent();

        $this->assertSoftDeleted('pipelines', [
            'id' => $pipeline->id,
        ]);
    });

    test('unauthorized user cannot delete pipeline', function () {
        $unauthorizedUser = User::factory()->create();
        $pipeline = Pipeline::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('pipelines.destroy', $pipeline));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted pipeline', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create();
        $pipeline->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.restore', $pipeline->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $pipeline->id]);

        $this->assertDatabaseHas('pipelines', [
            'id' => $pipeline->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted pipeline', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.restore', $pipeline->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent pipeline', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore pipeline', function () {
        $unauthorizedUser = User::factory()->create();
        $pipeline = Pipeline::factory()->create();
        $pipeline->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('pipelines.restore', $pipeline->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a pipeline', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create();
        $pipeline->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('pipelines.force-delete', $pipeline->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('pipelines', [
            'id' => $pipeline->id,
        ]);
    });

    test('can force delete a non-soft-deleted pipeline', function () {
        $user = adminUser();
        $pipeline = Pipeline::factory()->create();
        $pipeline->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('pipelines.force-delete', $pipeline->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('pipelines', [
            'id' => $pipeline->id,
        ]);
    });

    test('unauthorized user cannot force delete pipeline', function () {
        $unauthorizedUser = User::factory()->create();
        $pipeline = Pipeline::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('pipelines.force-delete', $pipeline->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple pipelines', function () {
        $user = adminUser();
        $pipelines = Pipeline::factory()->count(3)->create();
        $ids = $pipelines->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Pipeline deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('pipelines', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple pipeline', function () {
        $user = adminUser();
        $pipelines = Pipeline::factory()->count(3)->create();
        $pipelines->each->delete();
        $ids = $pipelines->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Pipeline restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('pipelines', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipelines.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
