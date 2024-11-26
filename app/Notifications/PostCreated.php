<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Post $post ,public User $user,public ?Group $group= null)
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
        ->subject('New Post Created')
        ->greeting('Hello!')
        ->lineIf(
            !!$this->group,
            'New Post has been added by user ' . ($this->user?->username ?? "unknown user") . ' on ' . ($this->group?->slug ?? "unknown group") . '.'
        )
        ->lineIf(
           !$this->group,
            'New Post has been added by user ' . ($this->user?->username ?? "unknown user") .' '
        )
        ->action('View Post', route('post.view', $this->post->id)) // Ensure 'post.view' is defined in routes
        ->line('Thank you for using our application!');

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
