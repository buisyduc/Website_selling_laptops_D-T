<!-- resources/views/auth/forgot-password.blade.php -->
<h2>Quên mật khẩu</h2>
<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="Nhập email của bạn" required>
    <button type="submit">Gửi liên kết đặt lại mật khẩu</button>
</form>

@if (session('status'))
    <p style="color: green">{{ session('status') }}</p>
@endif

@if ($errors->any())
    <p style="color: red">{{ $errors->first() }}</p>
@endif
