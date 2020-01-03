@notifications(['notifications' => $user->notifications])
@slot('title')
    {{ __('notifications.from', ['name' => $user->name]) }}
@endslot

Notificações Da Pessoa
@endnotifications

