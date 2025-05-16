<x-app>
    <div class="content bg-emerald-900 p-4" style="background-image: url('{{ asset('img/poster.jpg') }}');background-size: cover;background-repeat: no-repeat;" >
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-md mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-6xl mx-auto  flex">
            <div class=" w-full md:w-1/2">
                <form method="POST" action="{{ route('membership.register') }}" enctype="multipart/form-data" class="max-w-lg mx-auto p-6 bg-white shadow-md rounded-lg">
                    @csrf
                    <h1 class="text-2xl font-bold mb-4"> Membership Form</h1>
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                        <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="company" class="block text-gray-700 font-bold mb-2">Company:</label>
                        <input type="text" name="company" id="company" class="w-full px-3 py-2 border border-gray-300 rounded-md" >
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-gray-700 font-bold mb-2">Category:</label>
                        <input type="text" name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="mobile" class="block text-gray-700 font-bold mb-2">Mobile:</label>
                        <input type="text" name="mobile" id="mobile" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="bio" class="block text-gray-700 font-bold mb-2">Bio:</label>
                        <textarea type="text" name="bio" id="bio" class="w-full px-3 py-2 border border-gray-300 rounded-md" ></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                        <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="payment_details" class="block text-gray-700 font-bold mb-2">Payment Details:</label>
                        <textarea name="payment_details" id="payment_details" class="w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                    </div>

                    
                    <div class="mb-4">
                        <label for="payment_screenshot" class="block text-gray-700 font-bold mb-2">Payment Screenshot:</label>
                        <input type="file" name="payment_screenshot" id="payment_screenshot" class="w-full px-3 py-2 border border-gray-300 rounded-md" accept="image/*" required>
                        <p class="text-sm text-gray-500 mt-1">Upload an image of your payment receipt/screenshot (JPG, PNG, JPEG)</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="other_details" class="block text-gray-700 font-bold mb-2">Other Details:</label>
                        <textarea name="other_details" id="other_details" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>


                    <div class="mb-6">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 border-gray-300 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Register
                        </button>
                    </div>
                </form>

            </div>
            <div class="poster">
            </div>
        </div>
    </div>
</x-app>
