<?php

namespace App\Notifications;

use App\EmailUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailResetNotification extends Notification
{
  use Queueable;

  public EmailUpdate $emailUpdateRequest;

  /**
   * Create a new notification instance.
   *
   * @param EmailUpdate $emailUpdateRequest
   */
  public function __construct(EmailUpdate $emailUpdateRequest)
  {
    $this->emailUpdateRequest = $emailUpdateRequest;
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
    return (new MailMessage)->subject("Vip")->markdown('user.request.email_update', [
      'emailUpdateRequest' => $this->emailUpdateRequest,
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
      'emailUpdateRequest' => $this->emailUpdateRequest,
      'user' => $notifiable
    ];
  }
}
