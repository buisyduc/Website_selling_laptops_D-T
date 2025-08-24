<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $type; // loại thông báo: 'new' hay 'status'

    public function __construct($order, $type = 'new')
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type == 'new'
            ? 'Xác nhận đặt hàng #' . $this->order->id
            : 'Cập nhật trạng thái đơn hàng #' . $this->order->id;

        return $this->subject($subject)
            ->view('emails.order');
    }
}
