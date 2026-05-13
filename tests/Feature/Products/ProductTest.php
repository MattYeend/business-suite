<?php

use App\Models\Product;
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
        Product::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('products.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list products', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('products.index'));

        $response->assertForbidden();
    });

    test('guest cannot list products', function () {
        auth()->logout();
        
        $response = $this->getJson(route('products.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a product', function () {
        $user = adminUser();

        $data = [
            'sku' => 'SKU-0123',
            'name' => 'Technology',
            'description' => 'New Product',
            'status' => 'active',
            'price' => '12.99'
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment([
                'sku' => 'SKU-0123',
                'name' => 'Technology',
                'description' => 'New Product',
                'status' => 'active',
                'price' => '12.99'
            ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-0123',
            'name' => 'Technology',
            'description' => 'New Product',
            'status' => 'active',
            'price' => '12.99'
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot create product', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('products.store'), [
                'name' => 'Technology',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a product', function () {
        $user = adminUser();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('products.show', $product));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $product->id,
                'name' => $product->name,
            ]);
    });

    test('returns 404 for non-existent product', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('products.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view product', function () {
        $unauthorizedUser = User::factory()->create();
        $product = Product::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('products.show', $product));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a product', function () {
        $user = adminUser();
        $product = Product::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('products.update', $product),
                ['name' => 'New Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
        ]);
    });

    test('can update a product via patch', function () {
        $user = adminUser();
        $product = Product::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('products.patch', $product),
                ['name' => 'Patched Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('products.update', $product),
                ['name' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot update product', function () {
        $unauthorizedUser = User::factory()->create();
        $product = Product::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('products.update', $product),
                ['name' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a product', function () {
        $user = adminUser();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('products.destroy', $product));

        $response->assertNoContent();

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    });

    test('unauthorized user cannot delete product', function () {
        $unauthorizedUser = User::factory()->create();
        $product = Product::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('products.destroy', $product));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted product', function () {
        $user = adminUser();
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.restore', $product->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $product->id]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted product', function () {
        $user = adminUser();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.restore', $product->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent product', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore product', function () {
        $unauthorizedUser = User::factory()->create();
        $product = Product::factory()->create();
        $product->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('products.restore', $product->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a product', function () {
        $user = adminUser();
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('products.force-delete', $product->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    });

    test('can force delete a non-soft-deleted product', function () {
        $user = adminUser();
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('products.force-delete', $product->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    });

    test('unauthorized user cannot force delete product', function () {
        $unauthorizedUser = User::factory()->create();
        $product = Product::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('products.force-delete', $product->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple products', function () {
        $user = adminUser();
        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Product deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('products', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple product', function () {
        $user = adminUser();
        $products = Product::factory()->count(3)->create();
        $products->each->delete();
        $ids = $products->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Product restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('products', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('products.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
