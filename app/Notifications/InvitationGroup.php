<?php

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationGroup extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Group $group , public int $hours , public string $token )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('You have been invited to this group" '. $this->group->name .'".')
                    ->action('join to group', url(route('group.approve-invitation',$this->token)))
                    ->line('this link is avilable for "'. $this->hours . '" from now !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
