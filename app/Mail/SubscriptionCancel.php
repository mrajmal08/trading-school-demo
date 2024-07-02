<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCancel extends Mailable
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
            ->subject("Your School subscription is about to auto-renew")
            ->markdown('emails.subscancel', [
                'name' => $this->object->name,
                'url' => $this->object->url,
            ]);
    }
}
