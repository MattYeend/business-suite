<?php

use App\Models\BillOfMaterialItem;
use App\Models\Product;
use App\Models\BillOfMaterial;
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
    test('can list bill of material items', function () {
        BillOfMaterialItem::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('bill-of-material-items.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list bill of material items', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('bill-of-material-items.index'));

        $response->assertForbidden();
    });

    test('guest cannot list bill of material items', function () {
        auth()->logout();
        
        $response = $this->getJson(route('bill-of-material-items.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a bill of material item', function () {
        $user = adminUser();

        $billOfMaterial = BillOfMaterial::factory()->create();
        $product = Product::factory()->create();
        $part = Part::factory()->create();

        $data = [
            'bill_of_material_id' => $billOfMaterial->id,
            'product_id' => $product->id,
            'part_id' => $part->id,
            'quantity' => 10,
            'sequence' => 1,
            'notes' => 'Test notes',
            'is_optional' => 1,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment([
                'bill_of_material_id' => $billOfMaterial->id,
                'product_id' => $product->id,
                'part_id' => $part->id,
                'quantity' => '10.0000',
            ]);

        $this->assertDatabaseHas('bill_of_material_items', [
            'bill_of_material_id' => $billOfMaterial->id,
            'product_id' => $product->id,
            'part_id' => $part->id,
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'bill_of_material_id',
                'product_id',
                'part_id',
                'quantity',
            ]);
    });

    test('unauthorized user cannot create bill of material item', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('bill-of-material-items.store'), [
                'quantity' => 5,
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a bill of material item', function () {
        $user = adminUser();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('bill-of-material-items.show', $billOfMaterialItem));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $billOfMaterialItem->id,
                'quantity' => $billOfMaterialItem->quantity,
            ]);
    });

    test('returns 404 for non-existent bill of material item', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('bill-of-material-items.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view bill of material item', function () {
        $unauthorizedUser = User::factory()->create();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('bill-of-material-items.show', $billOfMaterialItem));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a bill of material item', function () {
        $user = adminUser();

        $billOfMaterialItem = BillOfMaterialItem::factory()->create([
            'quantity' => 5,
            'notes' => 'Old Notes',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('bill-of-material-items.update', $billOfMaterialItem),
                [
                    'quantity' => 10,
                    'notes' => 'New Notes',
                ]
            );

        $response->assertOk()
            ->assertJsonFragment([
                'quantity' => '10.0000',
                'notes' => 'New Notes',
            ]);

        $this->assertDatabaseHas('bill_of_material_items', [
            'id' => $billOfMaterialItem->id,
            'notes' => 'New Notes',
        ]);
    });

    test('can update a bill of material item via patch', function () {
        $user = adminUser();

        $billOfMaterialItem = BillOfMaterialItem::factory()->create([
            'notes' => 'Old Notes',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('bill-of-material-items.patch', $billOfMaterialItem),
                ['notes' => 'New Notes']
            );

        $response->assertOk()
            ->assertJsonFragment([
                'notes' => 'New Notes',
            ]);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();

        $billOfMaterialItem = BillOfMaterialItem::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('bill-of-material-items.update', $billOfMaterialItem),
                [
                    'quantity' => -5, // Invalid: negative quantity
                ]
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);
    });

    test('unauthorized user cannot update bill of material item', function () {
        $unauthorizedUser = User::factory()->create();

        $billOfMaterialItem = BillOfMaterialItem::factory()->create();

        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('bill-of-material-items.update', $billOfMaterialItem),
                ['notes' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a bill of material item', function () {
        $user = adminUser();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('bill-of-material-items.destroy', $billOfMaterialItem));

        $response->assertNoContent();

        $this->assertSoftDeleted('bill_of_material_items', [
            'id' => $billOfMaterialItem->id,
        ]);
    });

    test('unauthorized user cannot delete bill of material item', function () {
        $unauthorizedUser = User::factory()->create();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('bill-of-material-items.destroy', $billOfMaterialItem));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted bill of material item', function () {
        $user = adminUser();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();
        $billOfMaterialItem->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.restore', $billOfMaterialItem->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $billOfMaterialItem->id]);

        $this->assertDatabaseHas('bill_of_material_items', [
            'id' => $billOfMaterialItem->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted bill of material item', function () {
        $user = adminUser();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.restore', $billOfMaterialItem->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent bill of material item', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore bill of material item', function () {
        $unauthorizedUser = User::factory()->create();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();
        $billOfMaterialItem->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('bill-of-material-items.restore', $billOfMaterialItem->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a bill of material item', function () {
        $user = adminUser();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();
        $billOfMaterialItem->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('bill-of-material-items.force-delete', $billOfMaterialItem->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('bill_of_material_items', [
            'id' => $billOfMaterialItem->id,
        ]);
    });

    test('can force delete a non-soft-deleted bill of material item', function () {
        $user = adminUser();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();
        $billOfMaterialItem->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('bill-of-material-items.force-delete', $billOfMaterialItem->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('bill_of_material_items', [
            'id' => $billOfMaterialItem->id,
        ]);
    });

    test('unauthorized user cannot force delete bill of material item', function () {
        $unauthorizedUser = User::factory()->create();
        $billOfMaterialItem = BillOfMaterialItem::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('bill-of-material-items.force-delete', $billOfMaterialItem->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple bill of material items', function () {
        $user = adminUser();
        $billOfMaterialItems = BillOfMaterialItem::factory()->count(3)->create();
        $ids = $billOfMaterialItems->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'BOM Item deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('bill_of_material_items', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple bill of material items', function () {
        $user = adminUser();
        $billOfMaterialItems = BillOfMaterialItem::factory()->count(3)->create();
        $billOfMaterialItems->each->delete();
        $ids = $billOfMaterialItems->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'BOM Item restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('bill_of_material_items', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('bill-of-material-items.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});