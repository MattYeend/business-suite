<?php

use App\Models\Company;
use App\Models\CompanyAddress;
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
    test('can list company addresses', function () {
        CompanyAddress::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-addresses.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list company addresses', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('company-addresses.index'));

        $response->assertForbidden();
    });

    test('guest cannot list company addresses', function () {
        auth()->logout();
        
        $response = $this->getJson(route('company-addresses.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a company address', function () {
        $user = adminUser();

        $company = Company::factory()->create();
        
        $data = [
            'company_id' => $company->id,
            'address_line_1' => '1 Dave Street',
            'type' => 'office',
            'city' => 'City',
            'postal_code' => 'PO5 5SG',
            'country' => 'Country',
            'is_primary' => true,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment([
                'address_line_1' => '1 Dave Street',
                'type' => 'office',
            ]);

        $this->assertDatabaseHas('company_addresses', [
            'company_id' => $company->id,
            'address_line_1' => '1 Dave Street',
            'type' => 'office',
            'city' => 'City',
            'postal_code' => 'PO5 5SG',
            'country' => 'Country',
            'is_primary' => true,
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['address_line_1']);
    });

    test('unauthorized user cannot create company address', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-addresses.store'), [
                'address_line_1' => '1 Dave Street',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a company address', function () {
        $user = adminUser();
        $company = Company::factory()->create();
        $address = CompanyAddress::factory()->create([
            'company_id' => $company->id,
            'address_line_1' => '1 Dave Street',
            'type' => 'office',
            'city' => 'City',
            'postal_code' => 'PO5 5SG',
            'country' => 'Country',
            'is_primary' => true,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-addresses.show', $address));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $address->id,
                'company_id' => $company->id,
                'address_line_1' => '1 Dave Street',
                'type' => 'office',
                'city' => 'City',
                'postal_code' => 'PO5 5SG',
                'country' => 'Country',
            ]);
    });

    test('returns 404 for non-existent company address', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-addresses.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view company address', function () {
        $unauthorizedUser = User::factory()->create();
        $address = CompanyAddress::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('company-addresses.show', $address));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a company address', function () {
        $user = adminUser();
        $company = Company::factory()->create();
        $address = CompanyAddress::factory()->create([
            'company_id' => $company->id,
            'address_line_1' => '1 Dave Street',
            'type' => 'office',
            'city' => 'City',
            'postal_code' => 'PO5 5SG',
            'country' => 'Country',
            'is_primary' => true,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('company-addresses.update', $address),
                ['type' => 'warehouse', 'city' => 'New City'],
            );

        $response->assertOk()
            ->assertJsonFragment(['type' => 'warehouse', 'city' => 'New City']);

        $this->assertDatabaseHas('company_addresses', [
            'id' => $address->id,
            'company_id' => $company->id,
            'type' => 'warehouse',
            'city' => 'New City',
        ]);
    });

    test('can update a company address via patch', function () {
        $user = adminUser();
        $address = CompanyAddress::factory()->create([
            'type' => 'office',
            'city' => 'Old City',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('company-addresses.patch', $address),
                ['type' => 'warehouse']
            );

        $response->assertOk()
            ->assertJsonFragment(['type' => 'warehouse']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $address = CompanyAddress::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('company-addresses.update', $address),
                ['address_line_1' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['address_line_1']);
    });

    test('unauthorized user cannot update company address', function () {
        $unauthorizedUser = User::factory()->create();
        $address = CompanyAddress::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('company-addresses.update', $address),
                ['type' => 'warehouse']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a company address', function () {
        $user = adminUser();
        $address = CompanyAddress::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-addresses.destroy', $address));

        $response->assertNoContent();

        $this->assertSoftDeleted('company_addresses', [
            'id' => $address->id,
        ]);
    });

    test('unauthorized user cannot delete company address', function () {
        $unauthorizedUser = User::factory()->create();
        $address = CompanyAddress::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('company-addresses.destroy', $address));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted company address', function () {
        $user = adminUser();
        $address = CompanyAddress::factory()->create();
        $address->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.restore', $address->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $address->id]);

        $this->assertDatabaseHas('company_addresses', [
            'id' => $address->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted company address', function () {
        $user = adminUser();
        $address = CompanyAddress::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.restore', $address->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent company address', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore company address', function () {
        $unauthorizedUser = User::factory()->create();
        $address = CompanyAddress::factory()->create();
        $address->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-addresses.restore', $address->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a company address', function () {
        $user = adminUser();
        $address = CompanyAddress::factory()->create();
        $address->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-addresses.force-delete', $address->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('company_addresses', [
            'id' => $address->id,
        ]);
    });

    test('can force delete a non-soft-deleted company address', function () {
        $user = adminUser();
        $address = CompanyAddress::factory()->create();
        $address->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-addresses.force-delete', $address->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('company_addresses', [
            'id' => $address->id,
        ]);
    });

    test('unauthorized user cannot force delete company address', function () {
        $unauthorizedUser = User::factory()->create();
        $address = CompanyAddress::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('company-addresses.force-delete', $address->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple company addresses', function () {
        $user = adminUser();
        $addresses = CompanyAddress::factory()->count(3)->create();
        $ids = $addresses->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company address deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('company_addresses', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple company addresses', function () {
        $user = adminUser();
        $addresses = CompanyAddress::factory()->count(3)->create();
        $addresses->each->delete();
        $ids = $addresses->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company address restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('company_addresses', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-addresses.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
