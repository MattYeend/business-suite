<?php

use App\Models\Company;
use App\Models\CompanyContact;
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
    test('can list company contacts', function () {
        CompanyContact::factory()->count(3)->create();

        $user = adminUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-contacts.index'));

        $response->assertOk();
    });

    test('unauthorized user cannot list company contacts', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('company-contacts.index'));

        $response->assertForbidden();
    });

    test('guest cannot list company contacts', function () {
        auth()->logout();
        
        $response = $this->getJson(route('company-contacts.index'));

        $response->assertUnauthorized();
    });
});

describe('store', function () {
    test('can create a company contact', function () {
        $user = adminUser();

        $company = Company::factory()->create();
        
        $data = [
            'company_id' => $company->id,
            'first_name' => 'Dave',
            'last_name' => 'Smith',
            'is_primary' => true,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.store'), $data);

        $response->assertCreated()
            ->assertJsonFragment(['first_name' => 'Dave']);

        $this->assertDatabaseHas('company_contacts', [
            'company_id' => $company->id,
            'first_name' => 'Dave',
            'last_name' => 'Smith',
            'is_primary' => true,
        ]);
    });

    test('validation fails with missing required fields', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['first_name']);
    });

    test('unauthorized user cannot create company contact', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-contacts.store'), [
                'first_name' => 'Dave',
            ]);

        $response->assertForbidden();
    });
});

describe('show', function () {
    test('can view a company contact', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-contacts.show', $contact));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $contact->id,
                'first_name' => $contact->first_name,
            ]);
    });

    test('returns 404 for non-existent company contact', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('company-contacts.show', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot view company contact', function () {
        $unauthorizedUser = User::factory()->create();
        $contact = CompanyContact::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->getJson(route('company-contacts.show', $contact));

        $response->assertForbidden();
    });
});

describe('update', function () {
    test('can update a company contact', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create([
            'first_name' => 'Old',
            'last_name' => 'Name' 
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('company-contacts.update', $contact),
                ['first_name' => 'New'],
            );

        $response->assertOk()
            ->assertJsonFragment(['first_name' => 'New']);

        $this->assertDatabaseHas('company_contacts', [
            'id' => $contact->id,
            'first_name' => 'New',
            'last_name' => 'Name'
        ]);
    });

    test('can update a company contact via patch', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create(['first_name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson(
                route('company-contacts.patch', $contact),
                ['first_name' => 'Patched Name']
            );

        $response->assertOk()
            ->assertJsonFragment(['first_name' => 'Patched Name']);
    });

    test('validation fails with invalid data', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson(
                route('company-contacts.update', $contact),
                ['first_name' => '']
            );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['first_name']);
    });

    test('unauthorized user cannot update company contact', function () {
        $unauthorizedUser = User::factory()->create();
        $contact = CompanyContact::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->putJson(
                route('company-contacts.update', $contact),
                ['first_name' => 'Updated']
            );

        $response->assertForbidden();
    });
});

describe('destroy', function () {
    test('can soft delete a company contact', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-contacts.destroy', $contact));

        $response->assertNoContent();

        $this->assertSoftDeleted('company_contacts', [
            'id' => $contact->id,
        ]);
    });

    test('unauthorized user cannot delete company contact', function () {
        $unauthorizedUser = User::factory()->create();
        $contact = CompanyContact::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('company-contacts.destroy', $contact));

        $response->assertForbidden();
    });
});

describe('restore', function () {
    test('can restore a soft deleted company contact', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create();
        $contact->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.restore', $contact->id));

        $response->assertOk()
            ->assertJsonFragment(['id' => $contact->id]);

        $this->assertDatabaseHas('company_contacts', [
            'id' => $contact->id,
            'deleted_at' => null,
        ]);
    });

    test('cannot restore a non-deleted company contact', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.restore', $contact->id));

        $response->assertNotFound();
    });

    test('returns 404 for non-existent company contact', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.restore', 99999));

        $response->assertNotFound();
    });

    test('unauthorized user cannot restore company contact', function () {
        $unauthorizedUser = User::factory()->create();
        $contact = CompanyContact::factory()->create();
        $contact->delete();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-contacts.restore', $contact->id));

        $response->assertForbidden();
    });
});

describe('forceDelete', function () {
    test('can permanently delete a company contact', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create();
        $contact->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-contacts.force-delete', $contact->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('company_contacts', [
            'id' => $contact->id,
        ]);
    });

    test('can force delete a non-soft-deleted company contact', function () {
        $user = adminUser();
        $contact = CompanyContact::factory()->create();
        $contact->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(route('company-contacts.force-delete', $contact->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('company_contacts', [
            'id' => $contact->id,
        ]);
    });

    test('unauthorized user cannot force delete company contact', function () {
        $unauthorizedUser = User::factory()->create();
        $contact = CompanyContact::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->deleteJson(route('company-contacts.force-delete', $contact->id));

        $response->assertForbidden();
    });
});

describe('bulkDelete', function () {
    test('can bulk delete multiple company contacts', function () {
        $user = adminUser();
        $contacts = CompanyContact::factory()->count(3)->create();
        $ids = $contacts->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.bulk.delete'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company contacts deleted successfully',
                'deleted_count' => 3,
                'deleted_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('company_contacts', ['id' => $id]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.bulk.delete'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with non-existent ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.bulk.delete'), [
                'ids' => [99999],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.bulk.delete'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});

describe('bulkRestore', function () {
    test('can bulk restore multiple company contacts', function () {
        $user = adminUser();
        $contacts = CompanyContact::factory()->count(3)->create();
        $contacts->each->delete();
        $ids = $contacts->pluck('id')->toArray();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.bulk.restore'), [
                'ids' => $ids,
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Company contacts restored successfully',
                'restored_count' => 3,
                'restored_ids' => $ids,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('company_contacts', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }
    });

    test('validation fails with missing ids', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.bulk.restore'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    });

    test('validation fails with invalid id format', function () {
        $user = adminUser();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('company-contacts.bulk.restore'), [
                'ids' => ['not-a-number'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0']);
    });
});
