<?php

namespace App\Base\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection|Model $users;
    public string $title;
    public string $body;
    public string|null $icon_path;
    public string|null $target_type;
    public int|null $target_id;

    /**
     * Create a new event instance.
     *
     * @param string $title
     * @param string $body
     * @param string $icon_path
     * @param Collection|Model |Authenticatable  $users
     * @param string|null $target_type
     * @param int|null $target_id
     */
    public function __construct(Collection|Model|Authenticatable $users, string $title, string $body, string | null $icon_path = null, string | null $target_type = null, int | null $target_id = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->icon_path = $icon_path;
        $this->users = $users;
        $this->target_type = $target_type;
        $this->target_id = $target_id;
    }
}
