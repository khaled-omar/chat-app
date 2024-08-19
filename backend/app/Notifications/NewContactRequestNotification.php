<?php

namespace App\Notifications;

use App\Models\ContactRequest;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewContactRequestNotification extends Notification
{
    use Queueable;

    public ?string $entityType;

    public ?int $entityId;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ContactRequest $contactRequest)
    {
        $this->entityId = $this->contactRequest->id;
        $this->entityType = ContactRequest::class;
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
            'content' => __('You have received a new contact request from :company with subject :subject in the company inbox.', [
                'subject' => $this->contactRequest->subject,
                'company' => $this->contactRequest?->fromCompany?->name,
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
