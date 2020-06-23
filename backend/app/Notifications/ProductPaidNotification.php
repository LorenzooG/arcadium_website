<?php

namespace App\Notifications;

use App\Product;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

final class ProductPaidNotification extends Notification
{
  use Queueable;

  /**
   * @var Collection<Product> $products
   */
  private Collection $products;

  /**
   * Create a new notification instance.
   *
   * @param Collection<Product> $products
   */
  public final function __construct(Collection $products)
  {
    $this->products = $products;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param User $notifiable
   * @return array
   */
  public final function via(User $notifiable)
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param User $notifiable
   * @return MailMessage
   */
  public final function toMail(User $notifiable)
  {
    return (new MailMessage)
      ->subject(trans('notifications.product.paid.subject'))
      ->markdown('notifications.product.purchased', [
        'user' => $notifiable,
        'products' => $this->products,
      ]);
  }

  /**
   * Get the array representation of the notification.
   *
   * @param User $notifiable
   * @return array
   */
  public final function toArray(User $notifiable)
  {
    return [
      'products' => $this->products,
      'user' => $notifiable
    ];
  }
}
