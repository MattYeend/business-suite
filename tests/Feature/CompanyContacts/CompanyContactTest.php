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