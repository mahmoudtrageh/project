<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-purple-50 to-teal-50 animate-gradient p-4">
    <!-- Login Container -->
    <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden w-full max-w-md border border-gray-100">
        <!-- Header -->
        <div class="p-8 bg-gradient-to-r from-blue-600 to-indigo-600 animate-gradient">
            <!-- Logo Placeholder -->
            <div class="w-16 h-16 mx-auto mb-6 bg-white rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-user-shield text-2xl text-blue-600"></i>
            </div>
            <h2 class="text-3xl font-bold text-white text-center mb-2">Welcome Back</h2>
            <p class="text-blue-100 text-center">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        <form class="p-8 space-y-6" action="{{route('admin.login.post')}}" method="post">
            @csrf
            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        class="pl-10 w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        required
                    />
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        class="pl-10 w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        required
                    />
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-200"
                    />
                    <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                </div>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 transition duration-200">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition duration-200 hover:scale-[1.02] active:scale-[0.98] shadow-lg"
            >
                Sign In <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>
    </div>
</body>
</html>