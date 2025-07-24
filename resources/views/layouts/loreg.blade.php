<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: linear-gradient(135deg, #1f2937, #111827);
            position: relative;
            overflow: hidden; /* Hide overflow to prevent scrollbars from floating elements */
            font-family: 'Inter', sans-serif; /* Using Inter font as per instructions */
        }

        /* Geometric pattern for background */
        .geometric-pattern {
            position: absolute;
            inset: 0; /* Covers the entire body */
            background-image: radial-gradient(#4b5563 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.04;
            z-index: 0; /* Ensures it's behind other content */
        }

        /* Container for floating circles */
        .floating-elements .floating-circle {
            position: absolute;
            border-radius: 9999px; /* Makes them perfectly circular */
            opacity: 0.1; /* Semi-transparent */
            background-color: #ffffff; /* White color */
            animation: float 8s ease-in-out infinite; /* Apply float animation */
            filter: blur(5px); /* Add a subtle blur for a softer look */
        }

        /* Individual circle styles with different sizes and positions */
        .floating-circle.circle-1 {
            width: 200px;
            height: 200px;
            top: 5%;
            left: 10%;
            animation-delay: 0s; /* No delay for first circle */
        }

        .floating-circle.circle-2 {
            width: 150px;
            height: 150px;
            top: 70%;
            right: 10%;
            animation-delay: 2s; /* Stagger animation start */
        }

        .floating-circle.circle-3 {
            width: 100px;
            height: 100px;
            top: 40%;
            left: 80%;
            animation-delay: 4s;
        }

        .floating-circle.circle-4 {
            width: 120px;
            height: 120px;
            bottom: 10%;
            left: 30%;
            animation-delay: 6s;
        }

        /* Keyframe animation for floating effect */
        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            25% { transform: translateY(-15px) translateX(10px); }
            50% { transform: translateY(-30px) translateX(-10px); }
            75% { transform: translateY(-15px) translateX(10px); }
        }
    </style>
</head>
<body class="h-full font-sans text-white antialiased flex items-center justify-center p-6 min-h-screen relative">

    <!-- Background Patterns -->
    <div class="geometric-pattern"></div>

    <!-- Floating Circles -->
    <div class="floating-elements">
        <div class="floating-circle circle-1"></div>
        <div class="floating-circle circle-2"></div>
        <div class="floating-circle circle-3"></div>
        <div class="floating-circle circle-4"></div>
    </div>

    <div class="relative z-10 w-full max-w-md space-y-6 bg-white/10 backdrop-blur-lg p-8 rounded-xl shadow-xl border border-white/20">

        <!-- Login Form -->
        <div id="login-form" class="form-container">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-2">Welcome Back!</h2>
                <p class="text-white/80">Sign in to your account</p>
            </div>

            <form class="space-y-6">
                <div>
                    <label for="login-email" class="block text-sm font-medium text-white mb-1">Email address</label>
                    <input type="email" id="login-email" name="email" required
                           class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-white/70 transition duration-300 ease-in-out"
                           placeholder="you@example.com">
                </div>

                <div>
                    <label for="login-password" class="block text-sm font-medium text-white mb-1">Password</label>
                    <input type="password" id="login-password" name="password" required
                           class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-white/70 transition duration-300 ease-in-out"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-white">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-400 hover:text-blue-300 transition duration-300 ease-in-out">Forgot your password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:scale-105">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="text-center text-sm text-white/80">
                Don't have an account?
                <a href="#" id="show-register" class="font-medium text-blue-400 hover:text-blue-300 transition duration-300 ease-in-out">Sign up</a>
            </div>
        </div>

        <!-- Register Form -->
        <div id="register-form" class="form-container hidden">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-2">Join Synergy ERP!</h2>
                <p class="text-white/80">Create your account</p>
            </div>

            <form class="space-y-6">
                <div>
                    <label for="register-name" class="block text-sm font-medium text-white mb-1">Full Name</label>
                    <input type="text" id="register-name" name="name" required
                           class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-white/70 transition duration-300 ease-in-out"
                           placeholder="John Doe">
                </div>

                <div>
                    <label for="register-email" class="block text-sm font-medium text-white mb-1">Email address</label>
                    <input type="email" id="register-email" name="email" required
                           class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-white/70 transition duration-300 ease-in-out"
                           placeholder="you@example.com">
                </div>

                <div>
                    <label for="register-password" class="block text-sm font-medium text-white mb-1">Password</label>
                    <input type="password" id="register-password" name="password" required
                           class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-white/70 transition duration-300 ease-in-out"
                           placeholder="••••••••">
                </div>

                <div>
                    <label for="register-confirm-password" class="block text-sm font-medium text-white mb-1">Confirm Password</label>
                    <input type="password" id="register-confirm-password" name="confirm-password" required
                           class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-white/70 transition duration-300 ease-in-out"
                           placeholder="••••••••">
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out transform hover:scale-105">
                        Register
                    </button>
                </div>
            </form>

            <div class="text-center text-sm text-white/80">
                Already have an account?
                <a href="#" id="show-login" class="font-medium text-blue-400 hover:text-blue-300 transition duration-300 ease-in-out">Sign in</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="absolute bottom-4 left-0 right-0 text-center text-sm text-white/70 z-10">
        © 2025 Synergy ERP. All rights reserved.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const showRegisterLink = document.getElementById('show-register');
            const showLoginLink = document.getElementById('show-login');

            // Function to show login form and hide register form
            function showLoginForm() {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
            }

            // Function to show register form and hide login form
            function showRegisterForm() {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
            }

            // Event listeners for the links
            if (showRegisterLink) {
                showRegisterLink.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior
                    showRegisterForm();
                });
            }

            if (showLoginLink) {
                showLoginLink.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior
                    showLoginForm();
                });
            }

            // Initially show the login form (optional, but good for clarity)
            showLoginForm();
        });
    </script>
</body>
</html>
