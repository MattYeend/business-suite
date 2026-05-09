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