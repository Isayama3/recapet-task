<?php

namespace App\Base\Notification;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * @var array
     */
    protected array $notificationChannels = [];

    /**
     * @var NotificationRepository
     */
    private NotificationRepository $notificationRepository;

    /**
     * NotificationService constructor.
     *
     * @param NotificationRepository $notificationRepository

     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Add notification channel
     *
     * @param string $name
     * @param INotificationChannel $channel
     * @return void
     */
    public function addChannel(string $name, INotificationChannel $channel): void
    {
        $this->notificationChannels[$name] = $channel;
    }

    /**
     * Send notification to multiple users.
     *
     * @param array $channels  array of channels ['fcm', 'email', 'whatsapp',....]
     * @param Collection|Model|Authenticatable $users  // Collection of users from DB
     * @param string $title
     * @param string $body
     * @param string|null $icon_path
     * @param string|null $target_type
     * @param int|null $target_id
     * @return void
     */
    public function send(
        array $channels,
        Collection | Model | Authenticatable $users,
        string $title,
        string $body,
        ?string $icon_path = null,
        ?string $target_type = null,
        ?int $target_id = null
    ): void {
        foreach ($channels as $channelName) {
            $this->validateChannel($channelName);
            $this->notificationChannels[$channelName]->send($users, $title, $body, $icon_path, $target_type, $target_id);
            $this->notificationRepository->save($channelName, $users, $title, $body, $icon_path, $target_type, $target_id);
        }
    }

    /**
     * Validate channel
     *
     * @param string $channelName
     * @return void
     */
    protected function validateChannel(string $channelName): void
    {
        if (!isset($this->notificationChannels[$channelName])) {
            throw new \InvalidArgumentException("Channel '$channelName' not registered.");
        }
    }
}
