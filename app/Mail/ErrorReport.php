<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorReport extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $selection;
    public $description;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Сообщение об ошибке')
            ->onQueue('low')
            ->view('mails.error_report');
    }
}
