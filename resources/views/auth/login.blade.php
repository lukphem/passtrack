<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>School Portal Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">

<!-- HEADER -->
<div class="text-center py-8">
    <div class="flex flex-col items-center gap-3">

        {{-- LOGO --}}
        @if(config('school.logo'))
            <img src="{{ asset('storage/school/logo.jpg') }}"
                alt="School Logo"
                class="h-32 w-32 object-contain rounded-lg shadow bg-white p-2">
        @else
            <div class="h-20 w-20 flex items-center justify-center bg-blue-600 text-white rounded-lg shadow text-3xl">
                🎓
            </div>
        @endif

        {{-- SCHOOL NAME --}}
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">
                {{ config('school.name', 'School Name') }}
            </h1>
            <p class="text-gray-500 text-sm">
                Academic Portal Authentication System
            </p>
        </div>
    </div>
</div>

<!-- GLOBAL ERRORS -->
@if ($errors->any())
    <div class="max-w-md mx-auto mb-4">
        <div class="bg-red-100 text-red-700 p-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<!-- MAIN -->
<div class="flex justify-center items-center flex-1 px-2">

    <div class="grid md:grid-cols-2 gap-6 w-full max-w-5xl">

        <!-- LOGIN CARD -->
        <div class="bg-white p-8 rounded-2xl shadow-lg">

            <div class="text-center mb-6">
                <div class="mx-auto bg-blue-600 w-14 h-14 rounded-full flex items-center justify-center text-white text-2xl">
                    🎓
                </div>

                <h2 class="text-2xl font-bold mt-3">School Portal</h2>
                <p class="text-gray-500 text-sm">Sign in to access your account</p>
            </div>

            <!-- SESSION STATUS -->
            @if (session('status'))
                <div class="mb-4 text-green-600 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <!-- LOGIN (EMAIL / MATRIC) -->
                <div class="mb-4">
                    <label class="text-sm font-medium">Student ID / Email</label>
                    <input type="text" name="login"
                        value="{{ old('login') }}"
                        class="w-full mt-1 p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="Enter your ID or email">

                    @error('login')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div class="mb-4">
                    <label class="text-sm font-medium">Password</label>
                    <input type="password" name="password"
                        class="w-full mt-1 p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="Enter password">

                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- REMEMBER -->
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Remember me</span>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Sign In
                </button>

                <!-- FORGOT PASSWORD -->
                <div class="text-center mt-4 text-sm">
                    <a href="#" class="text-blue-600 hover:underline">Forgot Password?</a>
                </div>
            </form>
        </div>

        <!-- INFO PANEL -->
        <div class="bg-white p-8 rounded-2xl shadow-lg">

            <h3 class="font-bold text-lg mb-4">Quick Information</h3>

            <div class="space-y-4 text-sm">

                <div class="bg-blue-50 p-4 rounded-lg">
                    <b>Login Flexibility</b><br>
                    You can use Matric number or student email.
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <b>First Time Users</b><br>
                    Default password is your surname.
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <b>Security</b><br>
                    Always logout after use. Do not share credentials.
                </div>

                <div class="bg-orange-50 p-4 rounded-lg">
                    <b>Support</b><br>
                    ICT Helpdesk: Mon–Fri (8am–5pm)
                </div>

                <div class="bg-red-50 p-4 rounded-lg">
                    <b>Trouble Logging In?</b><br>
                    Check credentials or reset password.
                </div>

            </div>

        </div>

    </div>
</div>

<!-- FOOTER -->
<div class="text-center py-6 text-gray-500 text-sm">
    © {{ date('Y') }} {{ config('school.name', 'School Name') }} • ICT Directorate
</div>

</body>
</html>
