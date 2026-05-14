<?php

use App\Models\Product;
use App\Models\BillOfMaterial;
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
    test('can list bill of materials', function () {
        BillOfMaterial::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('bill-of-materials.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list billOfMaterials', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('bill-of-materials.index'));

        $response->assertForbidden();
    });

    test('guest cannot list billOfMaterials', function () {
        auth()->logout();
        
        $response = $this->getJson(route('bill-of-materials.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a bill of material', function () {
        $user = adminUser();

        $product = Product::factory()->create();

        $data = [
            'product_id' => $product->id,
            'bom_number' => 'BOM-123',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment(['bom_number' => 'BOM-123']);

        $this->assertDatabaseHas('bill_of_materials', [
            'product_id' => $product->id,
            'bom_number' => 'BOM-123',
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['product_id', 'bom_number']);
    });

    test('unauthorized user cannot createpipeline stage', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('bill-of-materials.store'), [
                'description' => 'Technology',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a bill of material', function () {
        $user = adminUser();
        $billOfMaterial = BillOfMaterial::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('bill-of-materials.show', $billOfMaterial));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $billOfMaterial->id,
                'bom_number' => $billOfMaterial->bom_number,
            ]);
    });

    test('returns 404 for non-existent bill of material', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('bill-of-materials.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view bill of material', function () {
        $unauthorizedUser = User::factory()->create();
        $billOfMaterial = BillOfMaterial::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('bill-of-materials.show', $billOfMaterial));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a bill of material', function () {
        $user = adminUser();

        $billOfMaterial = BillOfMaterial::factory()->create([
            'description' => 'Old Description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('bill-of-materials.update', $billOfMaterial),
                ['description' => 'New Description']
            );

        $response->assertOk()
            ->assertJsonFragment([
                'description' => 'New Description',
            ]);

        $this->assertDatabaseHas('bill_of_materials', [
            'id' => $billOfMaterial->id,
            'description' => 'New Description',
        ]);;
    });

    test('can update a bill of material via patch', function () {
        $user = adminUser();

        $billOfMaterial = BillOfMaterial::factory()->create([
            'description' => 'Old Description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('bill-of-materials.patch', $billOfMaterial),
                ['description' => 'New Description']
            );

        $response->assertOk()
            ->assertJsonFragment([
                'description' => 'New Description',
            ]);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();

        $billOfMaterial = BillOfMaterial::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('bill-of-materials.update', $billOfMaterial),
                [
                    'bom_number' => '',
                ]
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['bom_number']);
    });

    test('unauthorized user cannot update bill of material', function () {
        $unauthorizedUser = User::factory()->create();

        $billOfMaterial = BillOfMaterial::factory()->create();

        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('bill-of-materials.update', $billOfMaterial),
                ['description' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a bill of material', function () {
        $user = adminUser();
        $billOfMaterial = BillOfMaterial::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('bill-of-materials.destroy', $billOfMaterial));

        $response->assertNoContent();

        $this->assertSoftDeleted('bill_of_materials', [
            'id' => $billOfMaterial->id,
        ]);
    });

    test('unauthorized user cannot delete bill of material', function () {
        $unauthorizedUser = User::factory()->create();
        $billOfMaterial = BillOfMaterial::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('bill-of-materials.destroy', $billOfMaterial));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted bill of material', function () {
        $user = adminUser();
        $billOfMaterial = BillOfMaterial::factory()->create();
        $billOfMaterial->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.restore', $billOfMaterial->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $billOfMaterial->id]);

        $this->assertDatabaseHas('bill_of_materials', [
            'id' => $billOfMaterial->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted bill of material', function () {
        $user = adminUser();
        $billOfMaterial = BillOfMaterial::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.restore', $billOfMaterial->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent bill of materials', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore bill of materials', function () {
        $unauthorizedUser = User::factory()->create();
        $billOfMaterial = BillOfMaterial::factory()->create();
        $billOfMaterial->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('bill-of-materials.restore', $billOfMaterial->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a bill of material', function () {
        $user = adminUser();
        $billOfMaterial = BillOfMaterial::factory()->create();
        $billOfMaterial->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('bill-of-materials.force-delete', $billOfMaterial->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('bill_of_materials', [
            'id' => $billOfMaterial->id,
        ]);
    });

    test('can force delete a non-soft-deleted bill of material', function () {
        $user = adminUser();
        $billOfMaterial = BillOfMaterial::factory()->create();
        $billOfMaterial->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('bill-of-materials.force-delete', $billOfMaterial->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('bill_of_materials', [
            'id' => $billOfMaterial->id,
        ]);
    });

    test('unauthorized user cannot force delete bill of materials', function () {
        $unauthorizedUser = User::factory()->create();
        $billOfMaterial = BillOfMaterial::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('bill-of-materials.force-delete', $billOfMaterial->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple bill of materials', function () {
        $user = adminUser();
        $billOfMaterials = BillOfMaterial::factory()->count(3)->create();
        $ids = $billOfMaterials->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'BOM deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('bill_of_materials', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple bill of materials', function () {
        $user = adminUser();
        $billOfMaterials = BillOfMaterial::factory()->count(3)->create();
        $billOfMaterials->each->delete();
        $ids = $billOfMaterials->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'BOM restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('bill_of_materials', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-materials.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
