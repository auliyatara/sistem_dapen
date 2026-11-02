<?php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ProfilePhotoTest extends TestCase
{
    public function test_missing_account_photo_uses_default_image()
    {
        $this->actingAs(new User(['user_id' => 'photo-test', 'user_foto' => 'missing-photo-test.png']));
        $this->get(route('dashboard_api.profile_photo'))->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_header_loads_account_photo_without_waiting_for_javascript()
    {
        $this->actingAs(new User(['user_id' => 'photo-test']));
        $html = view('component.header')->render();
        $this->assertStringContainsString('src="'.route('dashboard_api.profile_photo').'"', $html);
    }
}
