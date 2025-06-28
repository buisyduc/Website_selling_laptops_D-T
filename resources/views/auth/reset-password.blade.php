<!-- resources/views/auth/reset-password.blade.php -->
<h2>Đặt lại mật khẩu mới</h2>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ request('email') }}">

    <input type="password" name="password" placeholder="Mật khẩu mới" required>
    <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>

    <button type="submit">Đặt lại mật khẩu</button>
</form>

@if ($errors->any())
    <p style="color: red">{{ $errors->first() }}</p>
@endif
