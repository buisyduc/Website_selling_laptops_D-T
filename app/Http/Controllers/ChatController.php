<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
     public function index()
    {
        return view('chat.index');
    }

    public function sendMessage(Request $request)
    {
        $message = strtolower($request->message);

        // Cấu hình các từ khóa và phản hồi tương ứng
        $responses = [
            'chào' => 'Xin chào! Tôi có thể giúp gì cho bạn?',
            'giá' => 'Bạn muốn hỏi giá sản phẩm nào?',
            'mua hàng' => 'Bạn có thể đặt hàng trực tiếp trên website hoặc gọi hotline 1900 xxx',
            'hết hàng' => 'Sản phẩm tạm thời hết hàng, bạn vui lòng quay lại sau nhé.',
            'cảm ơn' => 'Rất vui được hỗ trợ bạn!',
        ];

        $reply = 'Xin lỗi, tôi chưa hiểu bạn nói gì. Bạn có thể hỏi lại được không?';
        foreach ($responses as $keyword => $response) {
            if (str_contains($message, $keyword)) {
                $reply = $response;
                break;
            }
        }

        return response()->json(['reply' => $reply]);
    }
}
