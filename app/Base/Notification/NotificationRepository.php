<?php

namespace App\Base\Notification;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NotificationRepository
{
    /**
     * Save notifications to database for multiple users
     *
     * @param string $channel_name
     * @param Collection|Model|Authenticatable $users
     * @param string $title
     * @param string $body
     * @param string|null $icon_path
     * @param string|null $target_type
     * @param int|null $target_id
     * @return void
     */
    public function save(
        string $channel_name,
        Collection | Model | Authenticatable $users,
        string $title,
        string $body,
        ?string $icon_path = null,
        ?string $target_type = null,
        ?int $target_id = null
    ): void {
        if ($users instanceof Model || $users instanceof Authenticatable) {
            $users = collect([$users]);
        }

        foreach ($users as $user) {
            $user->notifications()->create([
                'channel_name' => $channel_name,
                'title' => $title,
                'body' => $body,
                'icon_path' => $icon_path,
                'notifiable_target_type' => $target_type,
                'notifiable_target_id' => $target_id,
            ]);
        }
    }
}
