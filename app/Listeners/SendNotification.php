<?php

namespace App\Listeners;

use App\Events\NotificationStore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotificationStore  $event
     * @return void
     */
    public function handle(NotificationStore $event)
    {
        //
    }
}
