<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class mailQuenMatKhau extends Mailable
{
    use Queueable, SerializesModels;
    public $view;
    public $title;
    public $info;

    public function __construct($view, $title, $info)
    {
        $this->view     = $view;
        $this->title    = $title;
        $this->info     = $info;
    }

    public function build()
    {
        return $this->subject($this->title)
                    ->view($this->view, ['info' => $this->info]);
    }

}
