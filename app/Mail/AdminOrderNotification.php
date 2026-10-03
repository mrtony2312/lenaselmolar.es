<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Nouvelle commande reçue #' . $this->order['order_number'])
            ->view('emails.admin_order_notification')
            ->with([
                'order' => $this->order
            ]);
    }
}
