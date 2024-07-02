<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Admin;
use App\Events\NewUserRegistration;
use App\Notifications\UserNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewUserRegistration
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
     * @param  \App\Events\NewUserRegistration  $event
     * @return void
     */
    public function handle(NewUserRegistration $event)
    {
        if($event->message['users'] == 'all'){
            $user =  User::get();
        }
        if($event->message['users'] != 'all'){
            $user = User::find($event->message['users']);
        }
        if(is_array($event->message['users'])){
            $user = User::whereIn('id',$event->message['users'])->get();
        }

        Notification::send($user, new UserNotification($event));
    }
}
