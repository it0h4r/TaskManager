<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold">Bienvenue</h3>
                <p class="text-gray-600 mt-1">Voici un resume rapide de tes taches.</p>
                <a href="{{ route('tasks.index') }}" class="inline-block mt-3 px-4 py-2 rounded border border-gray-300 text-gray-700">
                    Aller vers Mes taches
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">A faire</p>
                    <p class="text-2xl font-bold">{{ $counts['todo'] ?? 0 }}</p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">En cours</p>
                    <p class="text-2xl font-bold">{{ $counts['in_progress'] ?? 0 }}</p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Terminees</p>
                    <p class="text-2xl font-bold">{{ $counts['done'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
