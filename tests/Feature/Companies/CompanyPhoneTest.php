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