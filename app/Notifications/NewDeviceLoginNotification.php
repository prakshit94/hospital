<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDeviceLoginNotification extends Notification
{
    use Queueable;

    protected $metadata;

    public function __construct(array $metadata)
    {
        $this->metadata = $metadata;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Security Alert: New Login to Your Account')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your account was just accessed from a new device or browser.')
            ->line('**When:** ' . now()->format('d M Y h:i A'))
            ->line('**IP Address:** ' . $this->metadata['ip'])
            ->line('**Browser:** ' . $this->metadata['browser'])
            ->line('**Platform:** ' . $this->metadata['platform'])
            ->line('If this was you, you can safely ignore this email.')
            ->action('View Security Activity', route('profile.edit'))
            ->line('If you did not authorize this login, please secure your account immediately by changing your password.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New login detected',
            'message' => 'Your account was accessed from a new device: ' . $this->metadata['browser'] . ' on ' . $this->metadata['platform'],
            'action_url' => route('profile.edit'),
            'type' => 'security_alert',
        ];
    }
}
