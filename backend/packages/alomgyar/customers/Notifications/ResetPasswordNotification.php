<?php

namespace Alomgyar\Customers\Notifications;

use Alomgyar\Customers\Customer;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use Closure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var Closure|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var Closure|null
     */
    public static $toMailCallback;

    private TemplateContentService $templateContentService;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @param  TemplateContentService  $templateContentService
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url = Customer::whichStore($notifiable);
        $url .= '#action:newpass|token:'.$this->token.'|email:'.$notifiable->getEmailForPasswordReset();

        return $this->buildMailMessage($url, $notifiable);
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return MailMessage
     */
    protected function buildMailMessage($url, $notifiable)
    {
        $count = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        $contentParser = new ContentParserService();

        $model = TemplateContentService::create()->getTemplateContent('reset-password-notification', true);

        $subject = $contentParser->parseContent($model->subject, []);
        $body = $contentParser->parseContent($model->description ?? '', compact('url', 'count'));

        $storeId = $notifiable->store;

        return (new MailMessage)
            ->from($this->getFromAddress($storeId), $this->getFromName($storeId))
            ->replyTo($this->getReplyAddress($storeId))
            ->subject($subject)
            ->view('templates::email.common', compact('body', 'storeId'));
    }

    private function getFromAddress($storeId): string
    {
        return match ($storeId) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
            default => env('MAIL_FROM_ADDRESS'),
        };
    }

    private function getFromName($storeId): string
    {
        return match ($storeId) {
            1 => env('OLCSOKONYVEK_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            2 => env('NAGYKER_MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
            default => env('MAIL_FROM_NAME'),
        };
    }

    private function getReplyAddress($storeId): string
    {
        return match ($storeId) {
            1 => env('OLCSOKONYVEK_MAIL_REPLY_ADDRESS', env('MAIL_REPLY_ADDRESS')),
            default => env('MAIL_REPLY_ADDRESS'),
        };
    }
}
