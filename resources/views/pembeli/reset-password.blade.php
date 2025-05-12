<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Pembeli</title>
</head>
<body>
    <h1>Reset Password</h1>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form action="{{ route('pembeli.resetPassword') }}" method="post">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <label>Password Baru:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password:</label><br>
        <input type="password" name="password_confirmation" required><br><br>

        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
