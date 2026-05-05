<?php

use App\Models\CompanyIndustry;
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
    test('can list company industries', function () {
        CompanyIndustry::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-industries.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list company industries', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('company-industries.index'));

        $response->assertForbidden();
    });

    test('guest cannot list company industries', function () {
        auth()->logout();
        
        $response = $this->getJson(route('company-industries.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a company industry', function () {
        $user = adminUser();
        
        $data = [
            'name' => 'Technology',
            'slug' => 'technology',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'Technology']);

        $this->assertDatabaseHas('company_industries', [
            'name' => 'Technology',
            'slug' => 'technology',
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot create company industry', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-industries.store'), [
                'name' => 'Technology',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a company industry', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-industries.show', $industry));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $industry->id,
                'name' => $industry->name,
            ]);
    });

    test('returns 404 for non-existent company industry', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-industries.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view company industry', function () {
        $unauthorizedUser = User::factory()->create();
        $industry = CompanyIndustry::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('company-industries.show', $industry));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a company industry', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('company-industries.update', $industry),
                ['name' => 'New Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('company_industries', [
            'id' => $industry->id,
            'name' => 'New Name',
        ]);
    });

    test('can update a company industry via patch', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('company-industries.patch', $industry),
                ['name' => 'Patched Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('company-industries.update', $industry),
                ['name' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    test('unauthorized user cannot update company industry', function () {
        $unauthorizedUser = User::factory()->create();
        $industry = CompanyIndustry::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('company-industries.update', $industry),
                ['name' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a company industry', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-industries.destroy', $industry));

        $response->assertNoContent();

        $this->assertSoftDeleted('company_industries', [
            'id' => $industry->id,
        ]);
    });

    test('unauthorized user cannot delete company industry', function () {
        $unauthorizedUser = User::factory()->create();
        $industry = CompanyIndustry::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('company-industries.destroy', $industry));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted company industry', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create();
        $industry->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.restore', $industry->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $industry->id]);

        $this->assertDatabaseHas('company_industries', [
            'id' => $industry->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted company industry', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.restore', $industry->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent company industry', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore company industry', function () {
        $unauthorizedUser = User::factory()->create();
        $industry = CompanyIndustry::factory()->create();
        $industry->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-industries.restore', $industry->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a company industry', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create();
        $industry->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-industries.force-delete', $industry->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('company_industries', [
            'id' => $industry->id,
        ]);
    });

    test('can force delete a non-soft-deleted company industry', function () {
        $user = adminUser();
        $industry = CompanyIndustry::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-industries.force-delete', $industry->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('company_industries', [
            'id' => $industry->id,
        ]);
    });

    test('unauthorized user cannot force delete company industry', function () {
        $unauthorizedUser = User::factory()->create();
        $industry = CompanyIndustry::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('company-industries.force-delete', $industry->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple company industries', function () {
        $user = adminUser();
        $industries = CompanyIndustry::factory()->count(3)->create();
        $ids = $industries->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company industries deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('company_industries', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple company industries', function () {
        $user = adminUser();
        $industries = CompanyIndustry::factory()->count(3)->create();
        $industries->each->delete();
        $ids = $industries->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company industries restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('company_industries', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-industries.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
