<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\RoleAccessController;
use App\Models\Menu;
use App\Models\RoleAccess;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SidebarAccessTest extends TestCase
{
    use DatabaseTransactions;

    public function test_authorized_child_displays_its_parent_without_exposing_other_children()
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            'role_id' => 'sidebar-test', 'role_name' => 'Sidebar Test', 'role_status' => 1,
        ]);
        $attributes = ['menu_kode' => 'test_sidebar', 'menu_nama' => 'Test sidebar', 'menu_level' => 1,
            'menu_order' => 1, 'menu_parent' => '', 'menu_has_child' => 1, 'menu_status' => 1,
            'menu_icon' => '', 'menu_route' => ''];
        $parent = Menu::create($attributes);
        $child = Menu::create(array_merge($attributes, ['menu_kode' => 'test_child', 'menu_level' => 2,
            'menu_parent' => $parent->menu_id, 'menu_has_child' => 0]));
        Menu::create(array_merge($attributes, ['menu_kode' => 'test_hidden', 'menu_level' => 2,
            'menu_parent' => $parent->menu_id, 'menu_has_child' => 0]));
        RoleAccess::create(['role_access_role_id' => 'sidebar-test', 'role_access_menu_id' => $child->menu_id]);
        $user = new User(['user_id' => 'sidebar-user', 'user_role_id' => 'sidebar-test']);
        // The controller loads the logged-in user from the database.
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'user_id' => $user->user_id, 'user_role_id' => $user->user_role_id,
            'user_username' => 'sidebar-test', 'user_nama' => 'Sidebar Test',
            'user_email' => 'sidebar-test@example.test', 'password' => 'unused', 'user_status' => 1,
        ]);
        $this->actingAs($user);
        $response = (new RoleAccessController)->index();
        $this->assertCount(1, $response['data']);
        $this->assertSame($parent->menu_id, $response['data'][0]->menu_id);
        $this->assertCount(1, $response['data'][0]->children);
        $this->assertSame($child->menu_id, $response['data'][0]->children[0]->menu_id);
    }
}
