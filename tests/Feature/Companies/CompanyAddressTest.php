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
    test('can create a company contact', function () {
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

    test('unauthorized user cannot create company contact', function () {
        $unauthorizedUser = User::factory()->create();
        
        $response = $this->actingAs($unauthorizedUser, 'sanctum')
            ->postJson(route('company-addresses.store'), [
                'address_line_1' => '1 Dave Street',
            ]);

        $response->assertForbidden();
    });
});