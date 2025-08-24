<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Thông báo đơn hàng</title>
</head>

<body>
    <h2>Xin chào {{ $order->user->name ?? 'Khách hàng' }}</h2>

    @if ($type == 'new')
        <p>Cảm ơn bạn đã đặt hàng tại website của chúng tôi 🎉</p>
        <p>Mã đơn: <b>#{{ $order->id }}</b></p>
        <p>Tổng tiền:
            <b>{{ number_format($order->total_amount, 0, ',', '.') }}đ</b>
        </p>
        <p>Chúng tôi sẽ sớm xác nhận và xử lý đơn hàng của bạn.</p>
    @else
        <p>Đơn hàng <b>#{{ $order->id }}</b> của bạn đã được cập nhật trạng thái:</p>
        <p><b>{{ getOrderStatusLabel($order->status) }}</b></p>
        <p>Tổng tiền:
            <b>{{ number_format($order->total_amount, 0, ',', '.') }}đ</b>
        </p>

        @switch($order->status)
            @case('pending')
                <p>Đơn hàng của bạn đang chờ xác nhận ⏳</p>
            @break

            @case('processing_seller')
                <p>Đơn hàng của bạn đã được xác nhận ✅</p>
            @break

            @case('processing')
                <p>Đơn hàng của bạn đang được giao hàng 🚚</p>
            @break

            @case('shipping')
                <p>Đơn hàng đã được giao thành công 📦</p>
            @break

            @case('completed')
                <p>Đơn hàng đã hoàn tất 🎉 Cảm ơn bạn đã mua sắm!</p>
            @break

            @case('cancelled')
            @case('canceled')
                <p>Rất tiếc, đơn hàng của bạn đã bị hủy ❌</p>
            @break

            @case('returned')
                <p>Đơn hàng đã được trả hàng/hoàn tiền 🔄</p>
            @break
        @endswitch
    @endif

    <p>Trân trọng, <br> Đội ngũ hỗ trợ Website Bán laptop D&T</p>
</body>

</html>
