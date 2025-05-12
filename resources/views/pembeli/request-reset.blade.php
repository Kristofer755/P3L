<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password Pembeli</title>
</head>
<body>
    <h1>Form Lupa Password</h1>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form action="{{ route('pembeli.sendResetLink') }}" method="post">
        @csrf
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <button type="submit">Kirim Link Reset</button>
    </form>
</body>
</html>
