<?php

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

