<?php
namespace Tests\Feature;

use App\Models\{User, Role};
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class KepesertaanAccessTest extends TestCase
{
    public function test_all_fields_can_view_but_only_membership_and_super_admin_can_manage()
    {
        foreach (['Bidang Kepesertaan' => true, 'Kepesertaan' => true, 'Super Admin' => true, 'Admin' => true, 'Investasi' => false, 'Keuangan' => false] as $name => $allowed) {
            $user = new User(['user_id' => 'access-test']);
            $user->setRelation('role', new Role(['role_name' => $name, 'role_status' => 1]));
            $this->actingAs($user);
            $this->assertSame($allowed, Gate::allows('manage-kepesertaan'), $name);
            foreach (['kepesertaan', 'kepesertaan_link', 'iuran', 'total'] as $menu) {
                $this->assertSame(['view' => true, 'edit' => $allowed], app(\App\Http\Controllers\BaseController::class)->UserAccess($menu));
            }
            $user->role->role_status = 0;
            $this->assertFalse(Gate::allows('manage-kepesertaan'));
        }
    }
}
