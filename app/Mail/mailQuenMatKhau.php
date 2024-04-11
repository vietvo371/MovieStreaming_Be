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
    protected $data;

    public function __construct($data)
    {
        $this->data     = $data;

    }

    public function build()
    {
        return $this->subject('LẤY LẠI MẬT KHẨU CỦA BẠN!')
                    ->view('mail.quen_mat_khau', ['data' => $this->data]);
    }

}
