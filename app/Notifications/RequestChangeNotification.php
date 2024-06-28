<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestChangeNotification extends Notification
{
    use Queueable;

    protected $requester;
    protected $request;

    protected $type;

    protected $title;
    protected $message;

    public function __construct($requester, $request, $type)
    {
        $this->requester = $requester;
        $this->request = $request;
        $this->type = $type;
        if ($type == 'change') {
            $this->title = "طلب تعديل";
            $this->message = "لقد تلقيت طلب تعديل من {$requester->name}";
        } elseif ($type == 'submit') {
            $this->title = "طلب تسليم";
            $this->message = "لقد تلقيت طلب تسليم من {$requester->name}";
        } elseif ($type == 'cancel') {
            $this->title = "طلب الغاء";
            $this->message = "لقد تلقيت طلب الغاء من {$requester->name}";
        }
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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
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
            'title' => $this->title,
            'message' => $this->message,
            'url' => url('/api/jobs/' . $this->request->job->id),
        ];
    }
}
