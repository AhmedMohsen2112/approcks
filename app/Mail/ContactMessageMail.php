<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    private $message;
    private $reply;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
         $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
                return $this->view('emails.contact_message')
                        ->from('info@eservices-jzn.net')
                        ->subject('Khdmaty')
                        ->with([
                            'data' => $this->data,
        ]);
    }
}
