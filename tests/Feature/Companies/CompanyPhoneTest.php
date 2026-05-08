<?php

use App\Models\Company;
use App\Models\CompanyPhone;
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
    test('can list company phones', function () {
        CompanyPhone::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-phones.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list company phones', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('company-phones.index'));

        $response->assertForbidden();
    });

    test('guest cannot list company phones', function () {
        auth()->logout();
        
        $response = $this->getJson(route('company-phones.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a company phone', function () {
        $user = adminUser();

        $company = Company::factory()->create();
        
        $data = [
            'company_id' => $company->id,
            'type' => 'main',
            'number' => '01234567890',
            'is_primary' => true,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment([
                'type' => 'main',
                'number' => '01234567890',
            ]);

        $this->assertDatabaseHas('company_phones', [
            'company_id' => $company->id,
            'type' => 'main',
            'number' => '01234567890',
            'is_primary' => true,
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['company_id', 'type', 'number']);
    });

    test('unauthorized user cannot create company phone', function () {
        $unauthorizedUser = User::factory()->create();
        
        $company = Company::factory()->create();

        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-phones.store'), [
                'company_id' => $company->id,
                'type' => 'main',
                'number' => '01234567890',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a company phone', function () {
        $user = adminUser();
        $company = Company::factory()->create();
        $phone = CompanyPhone::factory()->create([
            'company_id' => $company->id,
            'type' => 'main',
            'number' => '01234567890',
            'is_primary' => true,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-phones.show', $phone));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $phone->id,
                'company_id' => $company->id,
                'type' => 'main',
                'number' => '01234567890',
                'is_primary' => true,
            ]);
    });

    test('returns 404 for non-existent company phone', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-phones.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view company phone', function () {
        $unauthorizedUser = User::factory()->create();
        $phone = CompanyPhone::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('company-phones.show', $phone));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a company phone', function () {
        $user = adminUser();
        $company = Company::factory()->create();
        $phone = CompanyPhone::factory()->create([
            'company_id' => $company->id,
            'type' => 'main',
            'number' => '01234567890',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('company-phones.update', $phone),
                ['type' => 'fax', 'number' => '09876543210'],
            );

        $response->assertOk()
            ->assertJsonFragment(['type' => 'fax', 'number' => '09876543210']);

        $this->assertDatabaseHas('company_phones', [
            'id' => $phone->id,
            'type' => 'fax',
            'number' => '09876543210',
        ]);
    });

    test('can update a company phone via patch', function () {
        $user = adminUser();
        $phone = CompanyPhone::factory()->create([
            'type' => 'main',
            'number' => '01234567890',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('company-phones.patch', $phone),
                ['type' => 'mobile']
            );

        $response->assertOk()
            ->assertJsonFragment(['type' => 'mobile']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $phone = CompanyPhone::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('company-phones.update', $phone),
                ['number' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['number']);
    });

    test('unauthorized user cannot update company phone', function () {
        $unauthorizedUser = User::factory()->create();
        $phone = CompanyPhone::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('company-phones.update', $phone),
                ['type' => 'mobile']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a company phone', function () {
        $user = adminUser();
        $phone = CompanyPhone::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-phones.destroy', $phone));

        $response->assertNoContent();

        $this->assertSoftDeleted('company_phones', [
            'id' => $phone->id,
        ]);
    });

    test('unauthorized user cannot delete company phone', function () {
        $unauthorizedUser = User::factory()->create();
        $phone = CompanyPhone::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('company-phones.destroy', $phone));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted company phone', function () {
        $user = adminUser();
        $phone = CompanyPhone::factory()->create();
        $phone->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.restore', $phone->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $phone->id]);

        $this->assertDatabaseHas('company_phones', [
            'id' => $phone->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted company phone', function () {
        $user = adminUser();
        $phone = CompanyPhone::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.restore', $phone->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent company phone', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore company phone', function () {
        $unauthorizedUser = User::factory()->create();
        $phone = CompanyPhone::factory()->create();
        $phone->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-phones.restore', $phone->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a company phone', function () {
        $user = adminUser();
        $phone = CompanyPhone::factory()->create();
        $phone->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-phones.force-delete', $phone->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('company_phones', [
            'id' => $phone->id,
        ]);
    });

    test('can force delete a non-soft-deleted company phone', function () {
        $user = adminUser();
        $phone = CompanyPhone::factory()->create();
        $phone->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-phones.force-delete', $phone->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('company_phones', [
            'id' => $phone->id,
        ]);
    });

    test('unauthorized user cannot force delete company phone', function () {
        $unauthorizedUser = User::factory()->create();
        $phone = CompanyPhone::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('company-phones.force-delete', $phone->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple company phones', function () {
        $user = adminUser();
        $phones = CompanyPhone::factory()->count(3)->create();
        $ids = $phones->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company phones deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('company_phones', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple company phones', function () {
        $user = adminUser();
        $phones = CompanyPhone::factory()->count(3)->create();
        $phones->each->delete();
        $ids = $phones->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company phones restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('company_phones', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-phones.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
