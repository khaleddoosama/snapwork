<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitedNotification extends Notification implements ShouldQueue
{
    use Queueable;


    protected $inviter;
    protected $invitee;
    protected $job;
    /**
     * Create a new notification instance.
     */
    public function __construct($inviter, $invitee, $job)
    {
        $this->inviter = $inviter;
        $this->invitee = $invitee;
        $this->job = $job;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("{$this->inviter->name} has invited you to join.")
            ->action('Accept Invitation', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'inviter_id' => $this->inviter->id,
            'inviter_name' => $this->inviter->name,
            'invitee_id' => $this->invitee->id,
            'job_id' => $this->job->id,
            'job_title' => $this->job->title,
            'message' => "لقد دعاك {$this->job->title} للعمل علي {$this->inviter->name}",
        ];
    }
}
