<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OperatorLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_operator_can_login_to_admin_but_cannot_manage_users(): void
    {
        Role::firstOrCreate(['name' => 'Operator']);

        $operator = User::factory()->create([
            'email' => 'operator',
            'password' => 'operator123',
        ]);

        $operator->assignRole('Operator');

        $this->assertTrue(Auth::attempt([
            'email' => 'operator',
            'password' => 'operator123',
        ]));

        $this->actingAs($operator)
            ->get('/admin')
            ->assertOk();

        $this->actingAs($operator)
            ->get('/admin/users')
            ->assertForbidden();
    }
}
