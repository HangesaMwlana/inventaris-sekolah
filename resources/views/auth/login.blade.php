<x-guest-layout>

    <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">
        Login
    </h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Email
            </label>
            <input type="email" name="email"
                   class="w-full px-4 py-2 border border-gray-300 
                          rounded-lg focus:ring-2 focus:ring-blue-500 
                          focus:outline-none"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Password
            </label>
            <input type="password" name="password"
                   class="w-full px-4 py-2 border border-gray-300 
                          rounded-lg focus:ring-2 focus:ring-blue-500 
                          focus:outline-none"
                   required>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 
                       rounded-lg hover:bg-blue-700 transition">
            Login
        </button>

    </form>

</x-guest-layout>
