<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SharePermissionUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Link $link,
        private readonly User $actor,
        private readonly string $oldPermission,
        private readonly string $newPermission
    ) {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ((bool) env('NOTIFICATIONS_MAIL_ENABLED', false)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'share_permission_updated',
            'link_id' => $this->link->id,
            'link_title' => $this->link->title,
            'old_permission' => $this->oldPermission,
            'new_permission' => $this->newPermission,
            'updated_by' => [
                'id' => $this->actor->id,
                'name' => $this->actor->name,
            ],
            'url' => route('links.show', $this->link),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your shared permission was updated')
            ->line("Permission changed from {$this->oldPermission} to {$this->newPermission}.")
            ->action('Open link', route('links.show', $this->link));
    }
}
