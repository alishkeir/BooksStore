<?php

namespace Modules\Admin\Emails;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class InternalEmail extends Mailable implements ShouldQueue
{
    private string $mailSubject;

    private string $mailBody;

    public function __construct(string $mailSubject, string $mailBody)
    {
        $this->mailSubject = $mailSubject;
        $this->mailBody = $mailBody;
    }

    public function build()
    {
        return $this
            ->subject($this->mailSubject)
            ->html($this->mailBody);
    }
}
