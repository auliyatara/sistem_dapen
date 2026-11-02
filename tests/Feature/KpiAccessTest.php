<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class KpiAccessTest extends TestCase
{
    public function test_admin_and_super_admin_cannot_manage_kpi_of_any_field()
    {
        $target = new Role(['role_id' => 'investasi', 'role_name' => 'Investasi', 'role_status' => 1]);
        foreach (['Admin', 'Super Admin'] as $name) {
            $admin = (new User(['user_role_id' => 'admin']))->setRelation('role', new Role(['role_id' => 'admin', 'role_name' => $name, 'role_status' => 1]));
            $this->assertFalse(Gate::forUser($admin)->allows('manage-kpi', $target));
            $admin->role->role_status = 0;
            $this->assertFalse(Gate::forUser($admin)->allows('manage-kpi', $target));
        }
    }
    public function test_management_is_limited_to_the_users_active_non_admin_role()
    {
        $investasi = new Role(['role_id' => 'investasi', 'role_name' => 'Investasi', 'role_status' => 1]);
        $keuangan = new Role(['role_id' => 'keuangan', 'role_name' => 'Keuangan', 'role_status' => 1]);
        $user = new User(['user_role_id' => 'investasi']);

        $this->assertTrue(Gate::forUser($user)->allows('manage-kpi', $investasi));
        $this->assertFalse(Gate::forUser($user)->allows('manage-kpi', $keuangan));
        $this->assertFalse(Gate::forUser(new User(['user_role_id' => 'admin']))->allows('manage-kpi', $investasi));

        $investasi->role_status = 0;
        $this->assertFalse(Gate::forUser($user)->allows('manage-kpi', $investasi));

        $admin = new Role(['role_id' => 'admin', 'role_name' => 'Super Admin', 'role_status' => 1]);
        $this->assertFalse(Gate::forUser(new User(['user_role_id' => 'admin']))->allows('manage-kpi', $admin));
    }
}
