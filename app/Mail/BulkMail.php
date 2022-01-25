<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $title, $name, $email, $address1, $address2;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $name, $email, $address1, $address2)
    {        
        $this->title=$title;
        $this->name=$name;
        $this->email=$email;
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
                ->markdown('template.thank_you_regitration', [
                    'title' => $this->title,                    
                    'name' => $this->name,
                    'email' => $this->email,
                    'address1' => $this->address1,
                    'address2' => $this->address2,
                ]);
    }
}
