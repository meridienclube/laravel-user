<?php

namespace ConfrariaWeb\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class UserUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = (Auth::check()) ? Auth::user() : NULL;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = isset($notifiable->settings['notifications']) ? $notifiable->settings['notifications'] : ['database'];

        foreach ($via as $k => $v) {
            if ($v == 'telegram') {
                /*telegram_user_id é o ID do chet do user telegram*/
                if (isset($notifiable->settings['telegram_user_id'])) {
                    $via[$k] = TelegramChannel::class;
                } else {
                    unset($via[$k]);
                }
            }
        }

        return $via;
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
            ->line('Uma pessoa foi atualizada com sucesso no sistema.')
            ->action('Ver ' . $notifiable->name, route('admin.users.show', $notifiable->id))
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
            'title' => __('users.name.updated.by', ['name' => $notifiable->name, 'by' => $this->user->name ?? '']),
            'notifiable' => $notifiable
        ];
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param mixed $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
            ->content('Your SMS message content');
    }

    public function toTelegram($notifiable)
    {
        $url = 'teeste.com';

        return TelegramMessage::create()
            // Optional recipient user id.
            //->to($notifiable->telegram_user_id)
            // Markdown supported.
            ->content("Hello there!\nYour invoice has been *PAID*")
            // (Optional) Inline Buttons
            ->button('View Invoice', $url)
            ->button('Download Invoice', $url);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('Un usuário foi atualizado no sistema');
    }
}
