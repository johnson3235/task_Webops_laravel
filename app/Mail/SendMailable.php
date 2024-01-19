<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function envelope()
    {
        return new Envelope(
            subject: 'Verify Email',
        );
    }

 /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         $email=$this->data['email'];
         $code=$this->data['code'];
         $expired_at=$this->data['expired_at'];
         return $this->view('verify-email',compact('email','code' ,'expired_at'));
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
