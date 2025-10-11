<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <x-guest-layout>
        <div class="min-h-screen flex items-center justify-center">
            <div class="w-full max-w-md">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
                    </div>
                    <div>
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required />
                    </div>
                    <div>
                        <button type="submit">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </x-guest-layout>
</body>
</html>
