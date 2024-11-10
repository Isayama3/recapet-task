<?php

namespace App\Base\Notification\Services;

use App\Base\Notification\INotificationChannel;
use App\Base\Services\FirebaseHandler;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FCMService implements INotificationChannel
{
    /**
     * Send notification
     *
     * @param Collection|Model|Authenticatable $users
     * @param string $title
     * @param string $body
     * @param string|null $icon_path
     * @param string|null $target_type
     * @param int|null $target_id
     * @return void
     */
    public function send(Collection|Model|Authenticatable $users, string $title, string $body, ?string $icon_path = null, ?string $target_type = null, ?int $target_id = null): void
    {
        if ($users instanceof Model || $users instanceof Authenticatable) {
            $users = collect([$users]);
        }

        $tokens = $users->pluck('fcm_token', 'id')->toArray();

        (new FirebaseHandler())->send(
            tokens: $tokens,
            title: $title,
            body: $body,
            icon_path: $icon_path,
            target_type: $target_type,
            target_id: $target_id
        );
    }
}
