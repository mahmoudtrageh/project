<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom gradient background */
        .gradient-bg {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <!-- Login Container -->
    <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-md">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600">
            <h2 class="text-2xl font-semibold text-white">Welcome Back</h2>
            <p class="text-gray-200">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        <form class="p-6" action="{{route('admin.login.post')}}" method="post">
            @csrf
            <!-- Email Input -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                />
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label for="remember" class="ml-2 text-sm text-gray-700">Remember me</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 px-4 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                Sign In
            </button>
        </form>
    </div>
</body>
</html>