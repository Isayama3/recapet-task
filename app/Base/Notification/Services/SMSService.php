<?php

namespace App\Base\Notification\Services;

use App\Base\Notification\INotificationChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SMSService implements INotificationChannel
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
    public function send(Collection | Model | Authenticatable $users, string $title, string $body, ?string $icon_path = null, ?string $target_type = null, ?int $target_id = null): void
    {
        // $phoneNumbers = array_column($users, 'phone');

        // (new SMSHandler())->send(
        //     phones: $phoneNumbers,
        //     message: $body
        // );
    }
}
