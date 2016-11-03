<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RegisterNotify extends Notification
{
    use Queueable;

    /** 認証コード*/
    private $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code)
    {
            $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('sentinel.register_user_title'))
            ->greeting($notifiable->name.trans('sentinel.register_user_name'))
            ->line(config('app.name').trans('sentinel.register_user_intro'))
            ->action(trans('sentinel.register_user_button'),
                url('activate', [base64_encode($notifiable->email), $this->code]))
            ->line(trans('sentinel.register_user_outro'));
    }
}
