<div class="w-64 bg-white text-gray-800 shadow-lg h-full overflow-y-auto">
    <!-- Close button for mobile -->
    <button onclick="toggleSidebar()" class="lg:hidden absolute right-4 top-4 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <div class="p-6 space-y-8">
        <div class="space-y-6">
            @auth
            @endauth
        </div>
    </div>
</div>
