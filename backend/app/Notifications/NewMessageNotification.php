<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public ?string $entityType;

    public ?int $entityId;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Message $message)
    {
        $this->entityId = $this->message->id;
        $this->entityType = Message::class;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(User $notifiable): array
    {
        return [
            'content' => __('You have received a message from :company in your company inbox.', [
                'company' => $this->message?->fromCompany?->name,
            ]),
        ];
    }

    /**
     * Determine if the notification should be sent.
     */
    public function shouldSend(User $notifiable, string $channel): bool
    {
        return ! NotificationModel::query()
            ->where('notifiable_id', $notifiable->id)
            ->where('notifiable_type', get_class($notifiable))
            ->where('entity_type', $this->entityType)
            ->where('entity_id', $this->entityId)
            ->exists();
    }
}
