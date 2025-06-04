<footer class="bg-[#013f3a] text-white py-12 mt-12">
    <div class="container mx-auto px-4">
        <div class="grid max-w-7xl mx-auto justify-center md:grid-cols-3 gap-8">
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-[#013f3a] text-white p-3 rounded-lg">
                        {{-- <i class="fas fa-network-wired text-2xl"></i> --}}
                    <img src="{{ asset('img/logo.png') }}" alt="logo" class="w-20 ">

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
                <h4 class="text-lg font-bold mb-4 text-[#ff8001]">Resources</h4>
                <div class="space-y-2">
                    <a href="{{ route('membership') }}" class="block text-gray-400 hover:text-[#ff8001] transition duration-300">Join CBN</a>
                    <a href="#testimonials" class="block text-gray-400 hover:text-[#ff8001] transition duration-300">Testimonials</a>
                    <a href="{{ route('privacy') }}" class="block text-gray-400 hover:text-[#ff8001] transition duration-300">Privacy Policy</a>
                    <a href="{{ route('csae') }}" class="block text-gray-400 hover:text-[#ff8001] transition duration-300">Child Safety Policy</a>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4 text-[#ff8001]">Contact Info</h4>
                <div class="space-y-3">
                    <p class="text-gray-400 flex items-center">
                        <i class="fas fa-phone mr-3 text-[#ff8001]"></i>+91 XXXXX XXXXX
                    </p>
                    <p class="text-gray-400 flex items-center">
                        <i class="fas fa-envelope mr-3 text-[#ff8001]"></i>info@cbnchhattisgarh.com
                    </p>
                    <p class="text-gray-400 flex items-start">
                        <i class="fas fa-map-marker-alt mr-3 mt-1 text-[#ff8001]"></i>Hotel Central Park, Bhilai
                    </p>
                    <div class="flex space-x-4 mt-2">
                        <a href="#" class="text-gray-400 hover:text-[#ff8001]">
                            <span class="sr-only">Facebook</span>
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#ff8001]">
                            <span class="sr-only">Twitter</span>
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#ff8001]">
                            <span class="sr-only">LinkedIn</span>
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-[#000c0a] mt-8 pt-8 text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} {{ config('app.name', 'CBN') }}. All rights reserved.</p>
            <p class="text-gray-500 text-sm mt-2">"From Businessman to Businessman – A local network to grow local business"</p>
        </div>
    </div>
</footer>
