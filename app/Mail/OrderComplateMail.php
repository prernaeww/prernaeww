<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderComplateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $title, $message, $address1, $address2, $orderItem, $store_name, $address, $reached, $sub_total, $total, $tax, $pickup_method, $currency, $pickup_notes, $vehicle_description;
    /**
     * Create a new link instance.
     *
     * @return void
     */
    public function __construct($title, $message, $address1, $address2, $orderItem, $store_name, $address, $reached, $sub_total, $total, $tax, $pickup_method, $currency, $pickup_notes, $vehicle_description)
    {        
        $this->title=$title;
        $this->message=$message;
        $this->address1=$address1;
        $this->address2=$address2;
        $this->orderItem=$orderItem;
        $this->store_name=$store_name;
        $this->address=$address;
        $this->reached=$reached;
        $this->sub_total=$sub_total;
        $this->total=$total;
        $this->tax=$tax;
        $this->pickup_method=$pickup_method;
        $this->currency=$currency;
        $this->pickup_notes=$pickup_notes;
        $this->vehicle_description=$vehicle_description;
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
                ->markdown('template.order_complate', [
                    'title' => $this->title,
                    'message' => $this->message,
                    'address1' => $this->address1,
                    'address2' => $this->address2,
                    'orderItem' => $this->orderItem,
                    'store_name' => $this->store_name,
                    'address' => $this->address,
                    'reached' => $this->reached,
                    'sub_total' => $this->sub_total,
                    'total' => $this->total, 
                    'tax' => $this->tax, 
                    'pickup_method' => $this->pickup_method,
                    'currency' => $this->currency,
                    'pickup_notes' => $this->pickup_notes,
                    'vehicle_description' => $this->vehicle_description,
                ]);
    }
}
