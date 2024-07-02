<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChallengePurchase extends Mailable
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
            ->subject("Email for successful challenge purchase")
            ->markdown('emails.challenge', [
                'name' => $this->object->name,
                'challenge' => $this->object->challangeName,
                'date' => $this->object->date,
                'price' => $this->object->price,
            ]);
    }
}
