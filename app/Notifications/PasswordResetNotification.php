<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;
    protected $token;
    protected $email;
    protected $name;
    protected $admin;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param $email
     * @param $name
     * @param bool $admin
     */
    public function __construct($token, $email,$name, $admin = false)
    {
        $this->token = $token;
        $this->email = $email;
        $this->name = $name;
        $this->admin = $admin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
//        $url = url('/password/reset/'.$this->token.'?email='.$this->email);
        $base_url = ($this->admin == true) ? config('parameters.front_url_cms') : config('parameters.front_url');
        $url = $base_url . '/password/reset?token=' . $this->token . '&email=' . $this->email;
        return (new MailMessage)
            ->subject('Reset your Laravel password')
            ->greeting('Hello ' . $this->name)
            ->action('Reset Password', url($url))
            ->line('You have requested to reset your account password. Follow the link below to reset it.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
