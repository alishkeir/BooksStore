<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacebookUserConversionMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    private $newPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromEmail = match ($this->user->store) {
            0 => 'info@alomgyar.hu',
            default => 'info@olcsokonyvek.hu'
        };

        $website = match ($this->user->store) {
            0 => 'https://alomgyar.hu/?forgottenpass=true',
            default => 'https://olcsokonyvek.hu/?forgottenpass=true'
        };

        return $this->from($fromEmail)
            ->view('emails.facebook-user-conversion')->with([
                'email' => $this->user->email,
                'newPassword' => $this->newPassword,
                'store' => $this->user->store,
                'website' => $website,
            ])->subject('Facebook belépés helyett - FONTOS!');
    }
}
