<header class="text-center mb-0">
    <h1 class="text-3xl font-bold leading-tight mt-0  shadow py-5">
        {{ config('app.name') }}
    </h1>
</header>
@if(session('success') || session('error'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition
        class="fixed bottom-4 right-4 z-50 w-80"
    >
        <div class="{{ session('success') ? 'bg-green-500' : 'bg-red-500' }} text-white py-2 px-4 rounded flex justify-between items-start relative">
            <div class="pr-6">
            <span class="font-semibold block">
                {{ session('success') ? 'Success:' : 'Error:' }}
            </span>
                <span>
                {{ session('success') ?? session('error') }}
            </span>
            </div>

            <button
                @click="show = false"
                class="text-white hover:text-gray-100 ml-2"
            >
                <svg class="w-4 h-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif
