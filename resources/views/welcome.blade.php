<x-app>
<body class="bg-gray-50" x-data="bgRotator()" x-init="start()">

    <!-- Hero Section -->
    <section id="home" class="relative text-white pt-32 pb-32 overflow-hidden min-h-[90vh]">
        <!-- Background with fade transition -->
        <template x-for="(image, index) in images" :key="index">
            <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
                :style="'background-image: url(img/banner/' + image + '.png)'"
                :class="{ 'opacity-100': index === counter, 'opacity-0': index !== counter }">
            </div>
        </template>

        <!-- Main Content -->
        <div class="relative z-10 container mx-auto px-4 max-w-8xl">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                
                <div class="space-y-6">
                    <button @click="paused = !paused"
                        class="text-sm mt-6 px-4 py-2 bg-white/10 border border-white/20 rounded hover:bg-white/20 transition flex items-center space-x-2">
                        <span>
                            <i class="fas fa-play" x-show="paused"></i>
                            <i class="fas fa-pause" x-show="!paused"></i>
                        </span>
                        {{-- <span x-text="paused ? 'Resume Background' : 'Pause Background'"></span> --}}
                    </button>
                    <h1 class="text-4xl md:text-6xl font-bold leading-tight">
                        Welcome to <span class="text-[#ff8001]">CBN</span>
                    </h1>
                    <h2 class="text-xl md:text-2xl text-[#ff8001]">
                        Chhattisgarh Business Network
                    </h2>
                    <p class="text-lg text-green-100 leading-relaxed">
                        The region's premier platform for entrepreneurs to connect, collaborate, and grow together.
                        Unite Chhattisgarh's business community through powerful weekly networking.
                    </p>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                        <p class="text-[#ff8001] font-semibold italic text-lg">
                            "From Businessman to Businessman – A local network to grow local business"
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#join"
                            class="bg-[#ff8001] text-[#013f3a] px-8 py-4 rounded-lg font-bold hover:bg-orange-400 transition duration-300 text-center">
                            <i class="fas fa-user-plus mr-2"></i>Join Now
                        </a>
                        <a href="#events"
                            class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-[#013f3a] transition duration-300 text-center">
                            <i class="fas fa-calendar mr-2"></i>Attend a Meeting
                        </a>
                    </div>

                    
                </div>
                
            </div>
        </div>
    </section>

        <!-- About Section -->
        <section id="about" class="py-16 max-w-7xl mx-auto bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-[#013f3a] mb-4">About CBN</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        CBN was founded to bridge the gap between local entrepreneurs in Chhattisgarh by creating a
                        structured, referral-driven business network where members help each other grow.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <div class="flex flex-col h-full space-y-8">
                        <div class="bg-gradient-to-br from-[#013f3a] to-indigo-600 text-white p-8 rounded-xl flex-1 flex flex-col">
                            <h3 class="text-2xl font-bold mb-4">
                                <i class="fas fa-eye mr-3"></i>Our Vision
                            </h3>
                            <p class="leading-relaxed flex-1">
                                To empower local entrepreneurs of Chhattisgarh by building a trusted business network
                                that promotes collaboration, growth, and opportunities through regional connection and
                                community support.
                            </p>
                        </div>

                        <div
                            class="bg-gradient-to-r from-[#ff8001]/10 to-orange-50 p-6 rounded-xl border-l-4 border-[#ff8001] flex-1 flex flex-col">
                            <h3 class="text-2xl font-bold text-[#ff8001] mb-4">
                                <i class="fas fa-star text-[#ff8001] mr-3"></i>Our Uniqueness
                            </h3>
                            <ul class="space-y-3 text-gray-600 flex-1">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-[#013f3a] mr-3 mt-1"></i>
                                    We operate 100% in Chhattisgarhi, ensuring comfort and relatability
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-[#013f3a] mr-3 mt-1"></i>
                                    Our community is built on trust, collaboration, and growth
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex flex-col h-full space-y-6">
                        <div class="bg-gradient-to-br from-[#ff8001] to-teal-600 text-white p-8 rounded-xl flex-1 flex flex-col">
                            <h3 class="text-2xl font-bold mb-4">
                                <i class="fas fa-bullseye mr-3"></i>Our Mission
                            </h3>
                            <p class="leading-relaxed flex-1">
                                Our mission is to create a strong and inclusive business ecosystem in Chhattisgarh where
                                business owners can connect, exchange referrals, grow their businesses, and learn from
                                each other — all while embracing the pride of our local language and culture.
                            </p>
                        </div>

                        <div
                            class="bg-gradient-to-r from-[#013f3a]/10 to-indigo-50 p-6 rounded-xl border-l-4 border-[#013f3a] flex-1 flex flex-col">
                            <h3 class="text-2xl font-bold text-[#013f3a] mb-4">
                                <i class="fas fa-lightbulb text-[#013f3a] mr-3"></i>Why CBN Was Started
                            </h3>
                            <p class="text-gray-600 leading-relaxed flex-1">
                                Many small and medium business owners in Chhattisgarh lack structured networking
                                platforms. CBN was born to fill that gap by providing a supportive, local language-based
                                ecosystem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Core Values Section -->
        <section class="py-16 bg-[#013f3a]  text-white">
            <div class="container max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Our Core Values</h2>
                <p class="text-xl text-green-100">The principles that guide our community</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white/10 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-white/20">
                <div class="bg-[#ff8001]/20 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-handshake text-[#ff8001] text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Trust & Integrity</h3>
                <p class="text-green-100">Building strong relationships based on mutual respect and ethical practices.</p>
                </div>

                <div class="bg-white/10 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-white/20">
                <div class="bg-[#ff8001]/20 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-users text-[#ff8001] text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Local Empowerment</h3>
                <p class="text-green-100">Supporting and uplifting local businesses across Chhattisgarh.</p>
                </div>

                <div class="bg-white/10 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-white/20">
                <div class="bg-[#ff8001]/20 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-rocket text-[#ff8001] text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Collaboration Over Competition</h3>
                <p class="text-green-100">Growing together through meaningful partnerships and referrals.</p>
                </div>

                <div class="bg-white/10 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-white/20">
                <div class="bg-[#ff8001]/20 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-heart text-[#ff8001] text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Cultural Pride</h3>
                <p class="text-green-100">Conducting business in Chhattisgarhi to keep our language and identity alive in the professional world.</p>
                </div>

                <div class="bg-white/10 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-white/20 md:col-span-2 lg:col-span-1">
                <div class="bg-[#ff8001]/20 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-[#ff8001] text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Commitment to Growth</h3>
                <p class="text-green-100">Helping every member grow personally and professionally through shared learning and support.</p>
                </div>
            </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="max-w-7xl mx-auto py-16 bg-white">
            <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-[#013f3a] mb-4">How It Works</h2>
                <p class="text-xl text-gray-600">Simple steps to join and benefit from our network</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Membership Process -->
                <div class="bg-gradient-to-br from-[#013f3a]/10 to-[#ff8001]/10 p-8 rounded-xl">
                <h3 class="text-2xl font-bold text-[#013f3a] mb-6">
                    <i class="fas fa-user-plus text-[#013f3a] mr-3"></i>Membership Process
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                    <div
                        class="bg-[#013f3a] text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">
                        1</div>
                    <span>Fill the online form</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <div
                        class="bg-[#013f3a] text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">
                        2</div>
                    <span>Attend an introductory meeting</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <div
                        class="bg-[#013f3a] text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">
                        3</div>
                    <span>Undergo basic orientation</span>
                    </div>
                </div>
                </div>

                <!-- Weekly Meeting Flow -->
                <div class="bg-gradient-to-br from-[#013f3a]/10 to-[#ff8001]/10 p-8 rounded-xl">
                <h3 class="text-2xl font-bold text-[#013f3a] mb-6">
                    <i class="fas fa-calendar-week text-[#013f3a] mr-3"></i>Weekly Meeting Flow
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-circle text-[#013f3a] text-xs"></i>
                    <span>Member Introductions</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-circle text-[#013f3a] text-xs"></i>
                    <span>Business Presentations</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-circle text-[#013f3a] text-xs"></i>
                    <span>Referral Exchange</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-circle text-[#013f3a] text-xs"></i>
                    <span>Training/Guest Sessions</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-circle text-[#013f3a] text-xs"></i>
                    <span>Networking Time</span>
                    </div>
                </div>
                </div>

                <!-- What Members Get -->
                <div class="bg-gradient-to-br from-[#ff8001]/10 to-[#013f3a]/10 p-8 rounded-xl">
                <h3 class="text-2xl font-bold text-[#ff8001] mb-6">
                    <i class="fas fa-gift text-[#ff8001] mr-3"></i>What Members Get
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-star text-[#ff8001]"></i>
                    <span>High-quality leads</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-star text-[#ff8001]"></i>
                    <span>Regular exposure</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-star text-[#ff8001]"></i>
                    <span>Business growth education</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-star text-[#ff8001]"></i>
                    <span>A trusted support system</span>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </section>
        
        <!-- Events Section -->
        <section id="events" class="py-16 max-w-7xl mx-auto bg-gray-50">
            <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-[#013f3a] mb-4">Events & Meetings</h2>
                <p class="text-xl text-gray-600">Join our weekly networking sessions</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="bg-[#013f3a]/10 p-4 rounded-lg mb-6">
                    <h3 class="text-2xl font-bold text-[#013f3a] mb-4">
                    <i class="fas fa-calendar-alt text-[#013f3a] mr-3"></i>Upcoming Meeting
                    </h3>
                    <p class="font-bold">{{$meeting->name}}</p> <br/>
                    <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-calendar text-[#013f3a]"></i>
                        <span class="font-semibold">
                            {{ \Carbon\Carbon::parse($meeting->date)->format('l, j F Y') }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock text-[#013f3a]"></i>
                        <span>
                            {{ \Carbon\Carbon::parse($meeting->start_time)->format('g:i A') }} to {{ \Carbon\Carbon::parse($meeting->end_time)->format('g:i A') }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-[#013f3a]"></i>
                        <span>{{$meeting->location}}</span>
                    </div>
                    </div>
                </div>
                <a href="{{ route('visitor') }}"
                    class="text-[#ffffff] bg-[#013f3a] px-6 py-3 rounded-lg inline-block hover:bg-orange-400 transition duration-300 font-bold">
                    <i class="fas fa-ticket-alt mr-2"></i>Reserve Your Seat
                </a>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg">
                <h3 class="text-2xl font-bold text-[#ff8001] mb-6">
                    <i class="fas fa-images text-[#ff8001] mr-3"></i>Past Highlights
                </h3>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    @foreach($gallery ?? [] as $index => $image)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 cursor-pointer group"
                             onclick="openLightbox({{ $index }})">
                            @if(($image['type'] ?? 'image') === 'image')
                                <div class="relative overflow-hidden">
                                    <img src="storage/{{ $image['file'] }}"
                                         alt="{{ $image['title'] ?? 'Gallery Image' }}"
                                         class="w-full h-24 object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <div class="relative overflow-hidden">
                                    <video class="w-full h-24 object-cover group-hover:scale-105 transition-transform duration-300"
                                           preload="metadata">
                                        <source src="storage/{{ $image['file'] }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($image['title']))
                                <div class="p-2">
                                    <p class="text-gray-700 text-xs font-medium line-clamp-2">{{ $image['title'] }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <p class="text-gray-600">Image galleries, videos, and member testimonials from past events will
                    be shared here.</p>
                    <div class="text-center mt-4">
                        <a href="{{ route('gallery') }}"
                           class="inline-block bg-[#013f3a] text-white px-6 py-3 rounded-lg font-bold hover:bg-[#ff8001] hover:text-[#013f3a] transition duration-300">
                            <i class="fas fa-images mr-2"></i>View Full Gallery
                        </a>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-16 bg-white">
            <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-[#013f3a] mb-4">What Our Members Say</h2>
                <p class="text-xl text-gray-600">Real experiences from real business owners</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-[#013f3a]/5 to-[#ff8001]/5 p-6 rounded-xl border-l-4 border-[#013f3a]">
                <div class="flex items-center mb-4">
                    <div
                    class="bg-[#013f3a] text-white rounded-full w-12 h-12 flex items-center justify-center mr-4">
                    <i class="fas fa-user"></i>
                    </div>
                    <div>
                    <h4 class="font-bold text-[#013f3a]">Rajesh Sharma</h4>
                    <p class="text-gray-600 text-sm">Manufacturing Business</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">"CBN gave me not just leads, but lifelong business friends. My
                    turnover doubled in just 6 months!"</p>
                <div class="flex text-[#ff8001] mt-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                </div>

                <div class="bg-gradient-to-br from-[#013f3a]/5 to-[#ff8001]/5 p-6 rounded-xl border-l-4 border-[#ff8001]">
                <div class="flex items-center mb-4">
                    <div
                    class="bg-[#ff8001] text-white rounded-full w-12 h-12 flex items-center justify-center mr-4">
                    <i class="fas fa-user"></i>
                    </div>
                    <div>
                    <h4 class="font-bold text-[#013f3a]">Priya Agarwal</h4>
                    <p class="text-gray-600 text-sm">Retail Business</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">"The networking opportunities and business education have been
                    invaluable for my growth."</p>
                <div class="flex text-[#ff8001] mt-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                </div>

                <div
                class="bg-gradient-to-br from-[#ff8001]/5 to-[#013f3a]/5 p-6 rounded-xl border-l-4 border-[#ff8001]">
                <div class="flex items-center mb-4">
                    <div
                    class="bg-[#ff8001] text-white rounded-full w-12 h-12 flex items-center justify-center mr-4">
                    <i class="fas fa-user"></i>
                    </div>
                    <div>
                    <h4 class="font-bold text-[#013f3a]">Suresh Patel</h4>
                    <p class="text-gray-600 text-sm">Service Industry</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">"CBN's local approach and Chhattisgarhi communication style made
                    networking comfortable and effective."</p>
                <div class="flex text-[#ff8001] mt-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                </div>
            </div>
            </div>
        </section>

        <!-- Join CBN Section -->
        <section id="join" class="py-16 bg-gradient-to-br from-[#013f3a] via-[#013f3a] to-[#ff8001] text-white">
            <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Join the CBN Network – Let's Grow Together!</h2>
                <p class="text-xl text-green-100">Take the first step towards expanding your business network</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 max-w-7xl mx-auto">
                <div class="space-y-6">
                <h3 class="text-2xl font-bold">Membership Benefits</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-[#ff8001] text-xl"></i>
                    <span>Access to exclusive CBN events and workshops</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-[#ff8001] text-xl"></i>
                    <span>Personal development and business training</span>
                    </div>
                    <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-[#ff8001] text-xl"></i>
                    <span>Connect with like-minded entrepreneurs</span>
                    </div>
                </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 border border-white/20">
                <form action="{{ route('membership.register') }}" method="POST" class="space-y-6" enctype='multipart/form-data' >
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Full Name *</label>
                        <input type="text" name="name" required
                        class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Business Name *</label>
                        <input type="text" name="company" required
                        class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]">
                    </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Business Category *</label>
                        <input type="text" name="category" required
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]"
                            placeholder="Enter your business category">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Phone Number *</label>
                        <input type="tel" name="mobile" required
                        class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]">
                    </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Email Address *</label>
                        <input type="email" name="email" required
                        class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">City / Location *</label>
                        <textarea name="address" required
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]"></textarea>
                    </div>
                    </div>

                    <div>
                    <label class="block text-sm font-semibold mb-2">Short Description of Your Business
                        *</label>
                    <textarea name="bio" rows="3" required
                        class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]"></textarea>
                    </div>

                    {{-- <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Years in Business</label>
                            <select name="years_in_business"
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-[#ff8001]">
                            <option value="">Select Range</option>
                            <option value="0-1">0-1 years</option>
                            <option value="2-5">2-5 years</option>
                            <option value="6-10">6-10 years</option>
                            <option value="10+">10+ years</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Preferred Meeting Location</label>
                            <select name="preferred_location"
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-[#ff8001]">
                            <option value="">Select Location</option>
                            <option value="bhilai">Bhilai</option>
                            <option value="raipur">Raipur</option>
                            <option value="other">Other</option>
                            </select>
                        </div>
                    </div> --}}


                    <div>
                        <label for="payment_details" class="block text-sm font-semibold mb-2">Payment Details *</label>
                        <textarea name="payment_details" id="payment_details" required
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]">{{ old('payment_details') }}</textarea>
                        <p class="text-sm text-white/70 mt-1">Include transaction ID, payment method, etc.</p>
                        @error('payment_details')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_screenshot" class="block text-sm font-semibold mb-2">Payment Screenshot *</label>
                        <input type="file" name="payment_screenshot" id="payment_screenshot" accept="image/*" required
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-[#ff8001]">
                        <p class="text-sm text-white/70 mt-1">Upload an image of your payment receipt/screenshot (JPG, PNG, JPEG). Max size: 5MB</p>
                        @error('payment_screenshot')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                    <label class="block text-sm font-semibold mb-2">Why do you want to join CBN?</label>
                    <textarea name="other_details" rows="3"
                        class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]"></textarea>
                    </div>

                    {{-- <div>
                    <label class="block text-sm font-semibold mb-2">Referral Code (if invited by a
                        member)</label>
                    <input type="text" name="referral_code"
                        class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#ff8001]">
                    </div> --}}

                    <div class="flex items-start space-x-3">
                    <input type="checkbox" name="agree_terms" required
                        class="mt-1 w-5 h-5 text-[#ff8001] rounded border-white/30 focus:ring-[#ff8001]">
                    <label class="text-sm">I agree to follow CBN values and attend weekly meetings.</label>
                    </div>

                    <button type="submit"
                    class="w-full bg-[#ff8001] text-[#013f3a] px-8 py-4 rounded-lg font-bold hover:bg-orange-400 transition duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>Join Now
                    </button>
                </form>
                </div>
            </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-[#013f3a] mb-4">Contact Us</h2>
                <p class="text-xl text-gray-600">Have Questions? We're Here to Help!</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <div class="space-y-8">
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-bold text-[#013f3a] mb-6">Get in Touch</h3>

                    <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-[#013f3a]/10 p-3 rounded-full">
                        <i class="fas fa-phone text-[#013f3a] text-xl"></i>
                        </div>
                        <div>
                        <h4 class="font-semibold text-[#013f3a]">Phone</h4>
                        <p class="text-gray-600">+91 XXXXX XXXXX</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="bg-[#ff8001]/10 p-3 rounded-full">
                        <i class="fas fa-envelope text-[#ff8001] text-xl"></i>
                        </div>
                        <div>
                        <h4 class="font-semibold text-[#013f3a]">Email</h4>
                        <p class="text-gray-600">info@cbnchhattisgarh.com</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="bg-[#ff8001]/10 p-3 rounded-full">
                        <i class="fas fa-map-marker-alt text-[#ff8001] text-xl"></i>
                        </div>
                        <div>
                        <h4 class="font-semibold text-[#013f3a]">Office/Meeting Venue</h4>
                        <p class="text-gray-600">Hotel Central Park, Bhilai</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="bg-[#013f3a]/10 p-3 rounded-full">
                        <i class="fab fa-whatsapp text-[#013f3a] text-xl"></i>
                        </div>
                        <div>
                        <h4 class="font-semibold text-[#013f3a]">WhatsApp</h4>
                        <a href="#" class="text-[#013f3a] hover:text-[#ff8001]">Send Message</a>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#013f3a] to-[#ff8001] text-white p-8 rounded-xl">
                    <h3 class="text-2xl font-bold mb-4">Ready to Network?</h3>
                    <p class="mb-6">Join our next meeting and experience the power of CBN networking in action.
                    </p>
                    <a href="#events"
                    class="bg-white text-[#013f3a] px-6 py-3 rounded-lg font-bold hover:bg-[#ff8001] hover:text-white transition duration-300 inline-block">
                    <i class="fas fa-calendar-plus mr-2"></i>Register for Meeting
                    </a>
                </div>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg">
                <form action="{{ route('store.feedback') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                    <label class="block text-sm font-semibold text-[#013f3a] mb-2">Full Name *</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 rounded-lg border border-[#013f3a]/30 focus:outline-none focus:ring-2 focus:ring-[#ff8001] focus:border-transparent">
                    </div>

                    <div>
                    <label class="block text-sm font-semibold text-[#013f3a] mb-2">Phone Number *</label>
                    <input type="tel" name="mobile" required
                        class="w-full px-4 py-3 rounded-lg border border-[#013f3a]/30 focus:outline-none focus:ring-2 focus:ring-[#ff8001] focus:border-transparent">
                    </div>

                    <div>
                    <label class="block text-sm font-semibold text-[#013f3a] mb-2">Email Address *</label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 rounded-lg border border-[#013f3a]/30 focus:outline-none focus:ring-2 focus:ring-[#ff8001] focus:border-transparent">
                    </div>

                    <div>
                    <label class="block text-sm font-semibold text-[#013f3a] mb-2">Your Message *</label>
                    <textarea name="message" rows="5" required
                        class="w-full px-4 py-3 rounded-lg border border-[#013f3a]/30 focus:outline-none focus:ring-2 focus:ring-[#ff8001] focus:border-transparent"></textarea>
                    </div>

                    <button type="submit"
                    class="w-full bg-[#ff8001] text-[#013f3a] px-8 py-4 rounded-lg font-bold hover:bg-[#013f3a] hover:text-white transition duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>Send Message
                    </button>
                </form>
                </div>
            </div>
            </div>
        </section>
 <!-- Alpine Component -->
    <script>
        function bgRotator() {
            return {
                images: [1, 2], // Add your banner image numbers
                counter: 0,
                paused: false,
                intervalId: null,

                start() {
                    this.intervalId = setInterval(() => {
                        if (!this.paused) {
                            this.counter = (this.counter + 1) % this.images.length;
                        }
                    }, 4000); // Change image every 4 seconds
                }
            };
        }
    </script>


</x-app>