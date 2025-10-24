<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail
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
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        $isSeller = $notifiable->isSeller();
        
        return (new MailMessage)
            ->subject($isSeller ? 'Verify Your Email - Start Selling on Musika Wedu' : 'Verify Your Email - Musika Wedu')
            ->greeting($isSeller ? 'Welcome to Musika Wedu, ' . $notifiable->name . '! 🌾' : 'Welcome to Musika Wedu, ' . $notifiable->name . '! 🛒')
            ->line('Thank you for joining Musika Wedu! To complete your registration and start using your account, please verify your email address.')
            ->when($isSeller, function (MailMessage $message) {
                return $message
                    ->line('🌾 **As a seller, you\'ll be able to:**')
                    ->line('• List your products and manage inventory')
                    ->line('• Connect with buyers across Zimbabwe')
                    ->line('• Grow your farming business with our platform')
                    ->line('• Access seller tools and analytics');
            })
            ->action($isSeller ? 'Verify Email & Start Selling' : 'Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in 60 minutes for security reasons.')
            ->line('**Important:** After clicking the verification link, you will be redirected to the login page where you can sign in with your email and password.')
            ->line('If you didn\'t create an account with Musika Wedu, please ignore this email.')
            ->salutation('Best regards, The Musika Wedu Team');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}