<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_fetch_notifications(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $admin->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'App\\Notifications\\NewOrderNotification',
            'data' => json_encode([
                'message' => 'New order received',
                'order_id' => 12,
            ]),
        ]);

        $response = $this->actingAs($admin, 'admin')->getJson(route('admin.notifications.index'));

        $response->assertOk()
            ->assertJsonPath('unread_count', 1)
            ->assertJsonCount(1, 'notifications');
    }

    public function test_admin_can_mark_all_notifications_as_read_via_json(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $admin->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'App\\Notifications\\NewOrderNotification',
            'data' => json_encode([
                'message' => 'New order received',
                'order_id' => 12,
            ]),
        ]);

        $response = $this->actingAs($admin, 'admin')->postJson(route('admin.notifications.readAll'));

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('unread_count', 0);
    }
}
