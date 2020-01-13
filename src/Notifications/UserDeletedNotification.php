<?php

namespace ConfrariaWeb\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return isset($notifiable->settings['notifications'])? $notifiable->settings['notifications'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('users.notification'))
            ->greeting('Olá ' . $notifiable->name . '!')
            ->line('Uma pessoa foi deletada com sucesso no sistema.')
            //->action('Ver ' . $notifiable->name, route('admin.users.show', $notifiable->id))
            ->line('Notificação gerada pelo sistema Meridien Clube!');
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
            'title' => __('users.deleted'),
            'notifiable' => $notifiable
        ];
    }
}
