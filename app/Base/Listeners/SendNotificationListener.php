<?php

namespace App\Base\Listeners;

use App\Base\Events\SendNotification;
use App\Base\Notification\NotificationService;
use App\Base\Notification\Services\FCMService;
use App\Base\Services\FirebaseHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    protected FirebaseHandler $firebaseHandler;
    protected notificationService $notificationService;
    /**
     * Create the event listener.
     *
     * @param FirebaseHandler $firebaseHandler
     */
    public function __construct(FirebaseHandler $firebaseHandler, NotificationService $notificationService)
    {
        $this->firebaseHandler = $firebaseHandler;
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     *
     * @param SendNotification $event
     * @return void
     */
    public function handle(SendNotification $event): void
    {
        $this->notificationService->addChannel('fcm', new FCMService());
        // $this->notificationService->addChannel('mail', new EmailService());
        $this->notificationService->send(
            ['fcm'],
            $event->users,
            $event->title,
            $event->body,
            $event->icon_path,
            $event->target_type,
            $event->target_id
        );
    }
}
