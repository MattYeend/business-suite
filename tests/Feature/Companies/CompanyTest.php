<?php

use App\Models\Company;
use App\Models\CompanyIndustry;
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
    test('can list companies', function () {
        Company::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('companies.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list companies', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('companies.index'));

        $response->assertForbidden();
    });

    test('guest cannot list companies', function () {
        auth()->logout();
        
        $response = $this->getJson(route('companies.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a company', function () {
        $user = adminUser();
        
        $industry = CompanyIndustry::factory()->create();

        $data = [
            'name' => 'Technology',
            'industry_id' => $industry->id,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'Technology']);

        $this->assertDatabaseHas('companies', [
            'name' => 'Technology',
            'industry_id' => $industry->id,
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot create company', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('companies.store'), [
                'name' => 'Technology',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a company', function () {
        $user = adminUser();
        $company = Company::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('companies.show', $company));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $company->id,
                'name' => $company->name,
            ]);
    });

    test('returns 404 for non-existent company', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('companies.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view company', function () {
        $unauthorizedUser = User::factory()->create();
        $company = Company::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('companies.show', $company));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a company', function () {
        $user = adminUser();
        $company = Company::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('companies.update', $company),
                ['name' => 'New Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'New Name',
        ]);
    });

    test('can update a company via patch', function () {
        $user = adminUser();
        $company = Company::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('companies.patch', $company),
                ['name' => 'Patched Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $company = Company::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('companies.update', $company),
                ['name' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot update company', function () {
        $unauthorizedUser = User::factory()->create();
        $company = Company::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('companies.update', $company),
                ['name' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a company', function () {
        $user = adminUser();
        $company = Company::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('companies.destroy', $company));

        $response->assertNoContent();

        $this->assertSoftDeleted('companies', [
            'id' => $company->id,
        ]);
    });

    test('unauthorized user cannot delete company', function () {
        $unauthorizedUser = User::factory()->create();
        $company = Company::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('companies.destroy', $company));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted company', function () {
        $user = adminUser();
        $company = Company::factory()->create();
        $company->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.restore', $company->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $company->id]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted company', function () {
        $user = adminUser();
        $company = Company::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.restore', $company->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent company', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore company', function () {
        $unauthorizedUser = User::factory()->create();
        $company = Company::factory()->create();
        $company->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('companies.restore', $company->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a company', function () {
        $user = adminUser();
        $company = Company::factory()->create();
        $company->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('companies.force-delete', $company->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    });

    test('can force delete a non-soft-deleted company', function () {
        $user = adminUser();
        $company = Company::factory()->create();
        $company->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('companies.force-delete', $company->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    });

    test('unauthorized user cannot force delete company', function () {
        $unauthorizedUser = User::factory()->create();
        $company = Company::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('companies.force-delete', $company->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple companies', function () {
        $user = adminUser();
        $companies = Company::factory()->count(3)->create();
        $ids = $companies->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('companies', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple company', function () {
        $user = adminUser();
        $companies = Company::factory()->count(3)->create();
        $companies->each->delete();
        $ids = $companies->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('companies', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('companies.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
