<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Welcome to IQA ClearVault</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        <style>
            /* Glitter cursor styles */
            * {
                cursor: default;
                /* Common cursor values:
                default - Default arrow cursor
                pointer - Hand cursor for clickable elements 
                text - I-beam cursor for text
                move - Move cursor for draggable elements
                not-allowed - Circle with slash for disabled elements
                wait - Loading/hourglass cursor
                crosshair - Precise selection cursor
                help - Question mark cursor
                zoom-in - Magnifying glass with plus
                zoom-out - Magnifying glass with minus
                grab - Open hand for draggable elements
                grabbing - Closed hand for actively dragging */
            }

            .glitter-cursor {
                width: 20px;
                height: 20px;
                background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0) 70%);
                border-radius: 50%;
                position: fixed;
                pointer-events: none;
                z-index: 9999;
            }

            .glitter-trail {
                width: 10px;
                height: 10px;
                background: rgba(255, 255, 255, 0.5);
                border-radius: 50%;
                position: fixed;
                pointer-events: none;
                animation: glitterFade 1s ease-out forwards;
            }

            @keyframes glitterFade {
                0% {
                    opacity: 0.5;
                    transform: scale(1);
                }
                100% {
                    opacity: 0;
                    transform: scale(0.1);
                }
            }

            body {
                background: linear-gradient(45deg, #1e3a8a, #3b82f6, #e0f7fa);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
                margin: 0;
                font-family: 'Figtree', Arial, sans-serif;
                height: 100vh;
                overflow-x: hidden;
            }

            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .header2 {
                display: flex;
                align-items: center;
                padding: 20px;
                color: white;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                animation: fadeInDown 1s ease-out;
            }

            .logo {
                width: 100px;
                height: auto;
                margin-right: 20px;
                transition: transform 0.3s ease;
            }

            .logo:hover {
                transform: scale(1.1);
            }

            .container {
                text-align: left;
                padding: 50px 70px;
                min-height: 80vh;
                max-width: 1200px;
                margin: 0 auto;
                animation: fadeIn 1s ease-out;
                position: relative;
                z-index: 1;
            }

            .header {
                margin-bottom: 50px;
            }

            h1 {
                font-size: 5rem;
                color: white;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                margin-bottom: 20px;
                animation: slideInLeft 1s ease-out;
            }

            p {
                font-size: 1.5rem;
                color: hsl(0, 0%, 90%);
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
                animation: slideInRight 1s ease-out;
            }

            .button-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-top: 40px;
                animation: fadeInUp 1s ease-out;
            }

            .button1, .button-google {
                padding: 12px 24px;
                background-color: rgba(255, 255, 255, 0.1);
                color: white;
                border: 2px solid white;
                border-radius: 30px;
                text-decoration: none;
                font-size: 1rem;
                transition: all 0.3s ease;
                backdrop-filter: blur(5px);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .button1:hover, .button-google:hover {
                background-color: white;
                color: #1e40af;
                transform: translateY(-3px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            .button-google img {
                width: 20px;
                height: 20px;
                margin-right: 10px;
            }

            footer {
                text-align: center;
                padding: 20px;
                color: white;
                font-size: 0.9rem;
                background-color: rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(5px);
                position: relative;
                z-index: 1;
            }

            @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
            @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes slideInLeft { from { opacity: 0; transform: translateX(-50px); } to { opacity: 1; transform: translateX(0); } }
            @keyframes slideInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }

            .floating-items {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 0;
            }

            .floating-item {
                position: absolute;
                display: block;
                list-style: none;
                animation: float 25s linear infinite;
                bottom: -150px;
                font-size: 24px;
            }

            .floating-item:nth-child(1) { left: 25%; animation-delay: 0s; }
            .floating-item:nth-child(2) { left: 10%; animation-delay: 2s; animation-duration: 12s; }
            .floating-item:nth-child(3) { left: 70%; animation-delay: 4s; }
            .floating-item:nth-child(4) { left: 40%; animation-delay: 0s; animation-duration: 18s; }
            .floating-item:nth-child(5) { left: 65%; animation-delay: 0s; }
            .floating-item:nth-child(6) { left: 75%; animation-delay: 3s; }
            .floating-item:nth-child(7) { left: 35%; animation-delay: 7s; }
            .floating-item:nth-child(8) { left: 50%; animation-delay: 15s; animation-duration: 45s; }
            .floating-item:nth-child(9) { left: 20%; animation-delay: 2s; animation-duration: 35s; }
            .floating-item:nth-child(10) { left: 85%; animation-delay: 0s; animation-duration: 11s; }

            @keyframes float {
                0% {
                    transform: translateY(0) rotate(0deg);
                    opacity: 1;
                }
                100% {
                    transform: translateY(-1000px) rotate(720deg);
                    opacity: 0;
                }
            }
        </style>
    </head>
    <body>
        <div class="glitter-cursor"></div>
        <div class="floating-items">
            <li class="floating-item">📚</li>
            <li class="floating-item">🎓</li>
            <li class="floating-item">✏️</li>
            <li class="floating-item">🖋️</li>
            <li class="floating-item">📝</li>
            <li class="floating-item">🔬</li>
            <li class="floating-item">🧪</li>
            <li class="floating-item">📐</li>
            <li class="floating-item">🖥️</li>
            <li class="floating-item">🧮</li>
        </div>
        <main>
            <div class="header2">
                <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                    <div style="display: flex; align-items: center;">
                        <img src="{{ asset('images/OMSCLogo.png') }}" alt="OMSC Logo" class="logo" />
                        <h3>OCCIDENTAL MINDORO STATE COLLEGE</h3>
                    </div>
                    <div>
                        <h2>DATE: {{ date('F j, Y') }}</h2>
                        <h2>DAY: {{ date('l') }}</h2>
                        <h2>TIME: {{ date('h:i A') }}</h2>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="header">
                    <h1 class="animate-title">IQA ClearVault</h1>
                    <p class="animate-text">Occidental Mindoro State College's secure vault for Institutional Quality Assurance data banking and clearance checklists management</p>
                </div>

                <style>
                    .animate-title {
                        opacity: 0;
                        transform: translateY(-20px);
                        animation: fadeInDown 1s ease forwards;
                    }

                    .animate-text {
                        opacity: 0;
                        transform: translateY(20px);
                        animation: fadeInUp 1s ease 0.5s forwards;
                    }

                    @keyframes fadeInDown {
                        from {
                            opacity: 0;
                            transform: translateY(-20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    @keyframes fadeInUp {
                        from {
                            opacity: 0;
                            transform: translateY(20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                </style>
                @if (Route::has('login'))
                    <div class="button-container">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="button1">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="button1">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="button1">Register</a>
                            @endif
                            <a href="{{ route('google.login') }}" class="button-google">
                                <img src="https://www.google.com/favicon.ico" alt="Google logo" />
                                Login with Google
                            </a>
                        @endauth
                        <!-- Add Tutorial Button -->
                        <button id="tutorialBtn" class="button1" style="display: flex; align-items: center;">
                            <span style="margin-right: 8px;">📹</span> Watch Tutorial
                        </button>
                    </div>
                @endif

                <!-- Tutorial Video Modal -->
                <div id="videoModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
                    <div class="bg-white/5 backdrop-blur-lg p-6 rounded-lg shadow-lg w-full max-w-5xl mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-light text-white">System Tutorial - Creating Account</h2>
                            <button onclick="closeVideoModal()" class="text-white/60 hover:text-white text-4xl">
                                &times;
                            </button>
                        </div>
                        <div class="relative" style="padding-bottom: 56.25%;">
                            <!-- Using local MP4 video file instead of YouTube embed -->
                            <video id="tutorialVideo" class="absolute inset-0 w-full h-full rounded-lg" 
                                   controls
                                   preload="metadata">
                                <source src="{{ asset('images/guide-video/Creating_Account.mp4') }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <p class="text-white/80 mt-4 text-sm">Learn how to create an account and get started with IQA ClearVault system.</p>
                    </div>
                </div>

            <div id="registerModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
                <div class="bg-white/10 backdrop-blur-lg p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
                    <h2 class="text-2xl font-light mb-6 text-white text-center" id="modalTitle">Select Role</h2>
                    
                    <div class="space-y-4">
                        <a href="{{ route('register', ['role' => 'faculty']) }}" 
                           class="block w-full p-4 bg-white/5 hover:bg-white/10 text-white rounded-lg border border-white/20 transition-all duration-200 text-center">
                            <span class="text-xl">👨‍🏫 Faculty</span>
                        </a>
                        
                        <a href="{{ route('registerAdminStaff', ['role' => 'admin']) }}" 
                           class="block w-full p-4 bg-white/5 hover:bg-white/10 text-white rounded-lg border border-white/20 transition-all duration-200 text-center">
                            <span class="text-xl">👨‍💼 Admin Staff</span>
                        </a>

                        <a href="{{ route('registerSA', ['role' => 'superadmin']) }}" 
                           id="secretOption"
                           class="hidden w-full p-4 text-white hover:text-red-300 transition-all duration-300 text-center mt-6 group relative">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative flex items-center justify-center">
                                <span class="text-xl mr-2 transform group-hover:rotate-12 transition-transform duration-300">🔐</span>
                                <span class="text-xl">Super Admin</span>
                            </div>
                            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-white/30 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                        </a>

                        <div class="text-sm text-white/80 bg-white/5 p-3 rounded-lg mt-4">
                            Note: Choose Faculty if you hold both positions
                        </div>
                        
                        <button onclick="closeRegisterModal()" 
                                class="w-full p-3 text-white/60 hover:text-white transition-colors duration-200">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <script>
                let clickCount = 0;
                
                document.querySelector('a[href="{{ route("register") }}"]').addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('registerModal').classList.remove('hidden');
                    document.getElementById('registerModal').classList.add('flex');
                });

                document.getElementById('modalTitle').addEventListener('click', function() {
                    clickCount++;
                    if (clickCount === 10) {
                        document.getElementById('secretOption').classList.remove('hidden');
                        this.innerHTML = "🔓 Secret Option Unlocked";
                        setTimeout(() => this.innerHTML = "Select Role", 5000);
                    }
                });

                function closeRegisterModal() {
                    document.getElementById('registerModal').classList.add('hidden');
                    document.getElementById('registerModal').classList.remove('flex');
                    clickCount = 0;
                    document.getElementById('secretOption').classList.add('hidden');
                }

                // Video Tutorial Modal Functions
                document.getElementById('tutorialBtn').addEventListener('click', function() {
                    document.getElementById('videoModal').classList.remove('hidden');
                    document.getElementById('videoModal').classList.add('flex');
                });

                function closeVideoModal() {
                    const video = document.getElementById('tutorialVideo');
                    // Pause the video when closing the modal
                    video.pause();
                    document.getElementById('videoModal').classList.add('hidden');
                    document.getElementById('videoModal').classList.remove('flex');
                }
            </script>
        </main>
        <footer>
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </footer>

        <script>
            // Create glitter cursor effect
            const cursor = document.querySelector('.glitter-cursor');
            let trails = [];

            document.addEventListener('mousemove', (e) => {
                cursor.style.left = e.clientX - 10 + 'px';
                cursor.style.top = e.clientY - 10 + 'px';

                // Create trail
                const trail = document.createElement('div');
                trail.className = 'glitter-trail';
                trail.style.left = e.clientX - 5 + 'px';
                trail.style.top = e.clientY - 5 + 'px';
                document.body.appendChild(trail);
                trails.push(trail);

                // Remove old trails
                if (trails.length > 20) {
                    trails[0].remove();
                    trails.shift();
                }

                // Remove trail after animation
                setTimeout(() => {
                    trail.remove();
                    trails = trails.filter(t => t !== trail);
                }, 1000);
            });
        </script>
         <!-- Loading Spinner -->
        <div id="loadingSpinner" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-80 hidden z-50">
            <div class="flex flex-col items-center">
                <div class="loader-container">
                    <div class="loader-ring"></div>
                    <div class="loader-ring"></div>
                    <div class="loader-ring"></div>
                </div>
                <div class="mt-6 text-white font-semibold text-xl tracking-wider animate-pulse">
                    Loading<span class="dot-1">.</span><span class="dot-2">.</span><span class="dot-3">.</span>
                </div>
            </div>
        </div>

        <style>
            .loader-container {
                position: relative;
                width: 100px;
                height: 100px;
            }

            .loader-ring {
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                border: 4px solid transparent;
                border-top-color: #ffffff;
                animation: spin 1.5s linear infinite;
            }

            .loader-ring:nth-child(2) {
                width: 80%;
                height: 80%;
                top: 10%;
                left: 10%;
                border-top-color: #60a5fa;
                animation-duration: 1.8s;
                animation-direction: reverse;
            }

            .loader-ring:nth-child(3) {
                width: 60%;
                height: 60%;
                top: 20%;
                left: 20%;
                border-top-color: #34d399;
                animation-duration: 2.1s;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .dot-1, .dot-2, .dot-3 {
                animation: dots 1.5s infinite;
                opacity: 0;
            }

            .dot-2 { animation-delay: 0.5s; }
            .dot-3 { animation-delay: 1s; }

            @keyframes dots {
                0%, 100% { opacity: 0; }
                50% { opacity: 1; }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loadingSpinner = document.getElementById('loadingSpinner');

                function showLoading() {
                    loadingSpinner.classList.remove('hidden');
                }

                function hideLoading() {
                    loadingSpinner.classList.add('hidden');
                }

                // Show loading spinner on page unload
                window.addEventListener('beforeunload', showLoading);

                // Hide loading spinner on page load
                window.addEventListener('load', hideLoading);
            });
        </script>
    </body>
</html>
