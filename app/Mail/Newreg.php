<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Newreg extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $object;
    public function __construct($mailObject)
    {
        $this->object = $mailObject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-replay@tradingschool.com', 'Trading School')
            ->subject("Your Trading School Account Has Been Successfully Created")
            ->markdown('emails.newreguser', [
                'name' => $this->object->name,
                'url' => $this->object->url,
                'username' => $this->object->username,
                'password' => $this->object->password,
            ]);
    }
}
