<?php

namespace App\Listeners;

use App\Events\UserNotify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use DB;
class UserNotifyListener
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
     * @param  UserNotify1  $event
     * @return void
     */
    public function handle(UserNotify $event)
    {
        $user = $event->user;
        DB::table('users')->whereId($user->id)->update(['status'=>1]);
        return $user;
    }
}
