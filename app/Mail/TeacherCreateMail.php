<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeacherCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public function __construct($mailData)
    {
        $this->data = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.reguser')->with('data', $this->data)->subject("Account Create");
    }
}
