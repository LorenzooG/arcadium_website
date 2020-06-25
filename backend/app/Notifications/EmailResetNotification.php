<?php

namespace App\Notifications;

use App\EmailUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailResetNotification extends Notification
{
  use Queueable;

  public string $token;

  /**
   * Create a new notification instance.
   *
   * @param string $token
   */
  public function __construct($token)
  {
    $this->token = $token;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param mixed $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param mixed $notifiable
   * @return MailMessage
   */
  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject(trans('notifications.email.reset.subject'))
      ->markdown('notifications.email.reset', [
        'token' => $this->token,
        'user' => $notifiable
      ]);
  }

  /**
   * Get the array representation of the notification.
   *
   * @param mixed $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      'token' => $this->token,
      'user' => $notifiable
    ];
  }
}
