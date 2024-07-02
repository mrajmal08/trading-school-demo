<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChallengeLive extends Mailable
{
    use Queueable, SerializesModels;

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
            ->subject("Email for challenge Live or successful")
            ->markdown('emails.challengelive', [
                'name' => $this->object->name,
            ]);
    }
}
