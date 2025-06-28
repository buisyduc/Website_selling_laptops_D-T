<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chat với D&T Bot</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .chat-box { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        .message { margin: 10px 0; }
        .user { text-align: right; color: blue; }
        .bot { text-align: left; color: green; }
        input { width: 100%; padding: 10px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="chat-box" id="chat-box">
        <div class="message bot">🤖 Chào bạn! Tôi là trợ lý ảo D&T. Hãy hỏi tôi bất kỳ điều gì nhé.</div>
    </div>
    <input type="text" id="message-input" placeholder="Nhập tin nhắn...">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#message-input').on('keypress', function(e) {
            if (e.which === 13) {
                let message = $(this).val();
                $('#chat-box').append(`<div class="message user">${message}</div>`);
                $(this).val('');

                $.ajax({
                    url: '{{ route("chat.send") }}',
                    method: 'POST',
                    data: { message },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#chat-box').append(`<div class="message bot">${response.reply}</div>`);
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                    }
                });
            }
        });
    </script>
</body>
</html>
