<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Common extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;

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
        $mail = $this->view($this->data->view);
        if(!empty($this->data->subject)) $mail = $mail->subject($this->data->subject);
        if(!empty($this->data->replyTo)) $mail = $mail->replyTo($this->data->reply_to);
        return $mail;
    }
}
