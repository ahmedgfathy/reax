<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('AI Debug Assistant') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Analysis Type</label>
                        <select id="analysisType" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="code">Code Analysis</option>
                            <option value="database">Database Schema Analysis</option>
                        </select>
                    </div>

                    <div id="codePathInput" class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">File Path</label>
                        <input type="text" id="codePath" class="w-full border-gray-300 rounded-md shadow-sm" 
                               placeholder="app/Http/Controllers/PropertyController.php">
                    </div>

                    <button onclick="analyze()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Analyze
                    </button>

                    <div id="result" class="mt-6 p-4 bg-gray-50 rounded-md hidden">
                        <h3 class="font-bold text-lg mb-2">Analysis Result</h3>
                        <pre id="analysisResult" class="whitespace-pre-wrap"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function analyze() {
            const type = document.getElementById('analysisType').value;
            const path = document.getElementById('codePath').value;
            
            fetch('{{ route("ai.analyze") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type, path })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').classList.remove('hidden');
                document.getElementById('analysisResult').textContent = data.analysis;
            });
        }
    </script>
    @endpush
</x-app-layout>
