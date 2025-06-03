<footer class="bg-gray-900 text-white py-12 mt-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-3 rounded-lg">
                        <i class="fas fa-network-wired text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">{{ config('app.name', 'CBN') }}</h3>
                        <p class="text-gray-400 text-sm">Chhattisgarh Business Network</p>
                    </div>
                </div>
                <p class="text-gray-400">
                    Connect, grow, and share success with our business networking community.<br>
                    Empowering local entrepreneurs through trusted business networking and community support.
                </p>
            </div>


            <div>
                <h4 class="text-lg font-bold mb-4">Resources</h4>
                <div class="space-y-2">
                    <a href="{{ route('membership') }}" class="block text-gray-400 hover:text-white transition duration-300">Join CBN</a>
                    <a href="#testimonials" class="block text-gray-400 hover:text-white transition duration-300">Testimonials</a>
                    <a href="{{ route('privacy') }}" class="block text-gray-400 hover:text-white transition duration-300">Privacy Policy</a>
                    <a href="{{ route('csae') }}" class="block text-gray-400 hover:text-white transition duration-300">Child Safety Policy</a>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4">Contact Info</h4>
                <div class="space-y-3">
                    <p class="text-gray-400 flex items-center">
                        <i class="fas fa-phone mr-3"></i>+91 XXXXX XXXXX
                    </p>
                    <p class="text-gray-400 flex items-center">
                        <i class="fas fa-envelope mr-3"></i>info@cbnchhattisgarh.com
                    </p>
                    <p class="text-gray-400 flex items-start">
                        <i class="fas fa-map-marker-alt mr-3 mt-1"></i>Hotel Central Park, Bhilai
                    </p>
                    <div class="flex space-x-4 mt-2">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Facebook</span>
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Twitter</span>
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">LinkedIn</span>
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} {{ config('app.name', 'CBN') }} - Chhattisgarh Business Network. All rights reserved.</p>
            <p class="text-gray-500 text-sm mt-2">"From Businessman to Businessman – A local network to grow local business"</p>
        </div>
    </div>
</footer>


    
    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuOverlay.classList.remove('hidden');
            setTimeout(() => {
                mobileMenu.classList.remove('translate-x-full');
            }, 10);
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.add('translate-x-full');
            setTimeout(() => {
                mobileMenuOverlay.classList.add('hidden');
            }, 300);
        });

        mobileMenuOverlay.addEventListener('click', (e) => {
            if (e.target === mobileMenuOverlay) {
                mobileMenu.classList.add('translate-x-full');
                setTimeout(() => {
                    mobileMenuOverlay.classList.add('hidden');
                }, 300);
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Form validation and styling
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Form submitted! This is a demo - please connect to your backend.');
            });
        });

        // Add scroll effect to navigation
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.classList.add('bg-white/95', 'backdrop-blur-sm');
            } else {
                nav.classList.remove('bg-white/95', 'backdrop-blur-sm');
            }
        });
    </script>