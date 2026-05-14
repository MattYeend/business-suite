<?php

use App\Models\Pipeline;
use App\Models\PipelineStage;
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
    test('can list pipeline stages', function () {
        PipelineStage::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('pipeline-stages.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list pipeline stages', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('pipeline-stages.index'));

        $response->assertForbidden();
    });

    test('guest cannot list pipeline stages', function () {
        auth()->logout();
        
        $response = $this->getJson(route('pipeline-stages.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a pipeline stage', function () {
        $user = adminUser();

        $pipeline = Pipeline::factory()->create();

        $data = [
            'pipeline_id' => $pipeline->id,
            'name' => 'Technology',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'Technology']);

        $this->assertDatabaseHas('pipeline_stages', [
            'pipeline_id' => $pipeline->id,
            'name' => 'Technology',
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot createpipeline stage', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('pipeline-stages.store'), [
                'name' => 'Technology',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a pipeline stage', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('pipeline-stages.show', $pipelineStage));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $pipelineStage->id,
                'name' => $pipelineStage->name,
            ]);
    });

    test('returns 404 for non-existentpipeline stage', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('pipeline-stages.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot viewpipeline stage', function () {
        $unauthorizedUser = User::factory()->create();
        $pipelineStage = PipelineStage::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('pipeline-stages.show', $pipelineStage));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a pipeline stage', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('pipeline-stages.update', $pipelineStage),
                ['name' => 'New Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('pipeline_stages', [
            'id' => $pipelineStage->id,
            'name' => 'New Name',
        ]);
    });

    test('can update a pipeline stage via patch', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('pipeline-stages.patch', $pipelineStage),
                ['name' => 'Patched Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('pipeline-stages.update', $pipelineStage),
                ['name' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot update pipeline stages', function () {
        $unauthorizedUser = User::factory()->create();
        $pipelineStage = PipelineStage::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('pipeline-stages.update', $pipelineStage),
                ['name' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a pipeline stage', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('pipeline-stages.destroy', $pipelineStage));

        $response->assertNoContent();

        $this->assertSoftDeleted('pipeline_stages', [
            'id' => $pipelineStage->id,
        ]);
    });

    test('unauthorized user cannot delete pipeline stages', function () {
        $unauthorizedUser = User::factory()->create();
        $pipelineStage = PipelineStage::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('pipeline-stages.destroy', $pipelineStage));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted pipeline stage', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create();
        $pipelineStage->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.restore', $pipelineStage->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $pipelineStage->id]);

        $this->assertDatabaseHas('pipelines', [
            'id' => $pipelineStage->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted pipeline stages', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.restore', $pipelineStage->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent pipeline stages', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore pipeline stages', function () {
        $unauthorizedUser = User::factory()->create();
        $pipelineStage = PipelineStage::factory()->create();
        $pipelineStage->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('pipeline-stages.restore', $pipelineStage->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a pipeline stage', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create();
        $pipelineStage->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('pipeline-stages.force-delete', $pipelineStage->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('pipeline_stages', [
            'id' => $pipelineStage->id,
        ]);
    });

    test('can force delete a non-soft-deleted pipeline stage', function () {
        $user = adminUser();
        $pipelineStage = PipelineStage::factory()->create();
        $pipelineStage->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('pipeline-stages.force-delete', $pipelineStage->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('pipeline_stages', [
            'id' => $pipelineStage->id,
        ]);
    });

    test('unauthorized user cannot force delete pipeline stages', function () {
        $unauthorizedUser = User::factory()->create();
        $pipelineStage = PipelineStage::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('pipeline-stages.force-delete', $pipelineStage->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple pipeline stages', function () {
        $user = adminUser();
        $pipelines = PipelineStage::factory()->count(3)->create();
        $ids = $pipelines->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'PipelineStage deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('pipeline_stages', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple pipeline stages', function () {
        $user = adminUser();
        $pipelines = PipelineStage::factory()->count(3)->create();
        $pipelines->each->delete();
        $ids = $pipelines->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Pipeline stage restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('pipeline_stages', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('pipeline-stages.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
