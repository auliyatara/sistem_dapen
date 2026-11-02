<?php

namespace Tests\Feature;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Tests\TestCase;

class KepegawaianAccessTest extends TestCase
{
    public function test_view_only_user_cannot_add_edit_or_delete_employees()
    {
        $this->actingAs(new User(['user_id' => 'access-test']));
        $this->mock(BaseController::class, function ($mock) {
            $mock->shouldReceive('UserAccess')->with('kepegawaian')->andReturn(['view' => true, 'edit' => false]);
        });
        $this->get(route('kepegawaian.index'))->assertOk()
            ->assertDontSee('Tambah Data')->assertDontSee('id="formData"', false)
            ->assertDontSee('href="javascript:onEdit', false)->assertDontSee('href="javascript:onDelete', false)
            ->assertSee('pegawai_detail_modal');
        $this->postJson(route('kepegawaian_api.store'), [])->assertForbidden();
        $this->patchJson(route('kepegawaian_api.update', 'access-test'), [])->assertForbidden();
        $this->deleteJson(route('kepegawaian_api.destroy', 'access-test'))->assertForbidden();
    }

    public function test_editor_sees_management_controls()
    {
        $this->actingAs(new User(['user_id' => 'access-test']));
        $this->mock(BaseController::class, function ($mock) {
            $mock->shouldReceive('UserAccess')->with('kepegawaian')->andReturn(['view' => true, 'edit' => true]);
        });
        $this->get(route('kepegawaian.index'))->assertOk()->assertSee('Tambah Data')->assertSee('id="formData"', false);
    }

    public function test_user_without_view_access_is_denied()
    {
        $this->actingAs(new User(['user_id' => 'access-test']));
        $this->mock(BaseController::class, function ($mock) {
            $mock->shouldReceive('UserAccess')->with('kepegawaian')->andReturn(['view' => false, 'edit' => false]);
        });
        $this->get(route('kepegawaian.index'))->assertForbidden();
        $this->getJson(route('kepegawaian_api.index'))->assertForbidden();
        $this->getJson(route('kepegawaian_api.show', 'access-test'))->assertForbidden();
        $this->get(route('kepegawaian_api.lampiran', 'test.pdf'))->assertForbidden();
    }
}
