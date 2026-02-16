<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkSharedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Link $link,
        private readonly User $actor,
        private readonly string $permission
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
            'type' => 'link_shared',
            'link_id' => $this->link->id,
            'link_title' => $this->link->title,
            'permission' => $this->permission,
            'shared_by' => [
                'id' => $this->actor->id,
                'name' => $this->actor->name,
            ],
            'url' => route('links.show', $this->link),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A link was shared with you')
            ->line("{$this->actor->name} shared a link with {$this->permission} permission.")
            ->action('Open link', route('links.show', $this->link));
    }
}
