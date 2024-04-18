<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioMmsMessage;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Twilio\Rest\Client;
class LoginNeedVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */




    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        $login_code = rand(111111, 999999);

        $notifiable->update([
            'login_code' => $login_code
        ]);
        $message = "Your Riding App OTP code is {$login_code}, and don't share this with anyone!";
        return (new TwilioSmsMessage())
            ->content($message);
    }



    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
