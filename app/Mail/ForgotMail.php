<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $title, $message, $address1, $address2;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $message, $address1, $address2)
    {        
        $this->title=$title;
        $this->message=$message;
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
                ->markdown('template.forgot_password', [
                    'title' => $this->title,
                    'message' => $this->message,
                    'address1' => $this->address1,
                    'address2' => $this->address2,
                ]);
    }
}
