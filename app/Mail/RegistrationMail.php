<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $title, $link, $address1, $address2;
    /**
     * Create a new link instance.
     *
     * @return void
     */
    public function __construct($title, $link, $address1, $address2)
    {        
        $this->title=$title;
        $this->link=$link;
        $this->address1=$address1;
        $this->address2=$address2;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->from('eww@abctogo.com')
         ->subject($this->title)
                ->markdown('template.welcome_account', [
                    'title' => $this->title,
                    'link' => $this->link,
                    'address1' => $this->address1,
                    'address2' => $this->address2,
                ]);
    }
}
