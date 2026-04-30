<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mes taches
            </h2>


        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('tasks.create') }}" class="inline-block px-4 py-2 rounded font-semibold"
                            style="background:#1d4ed8;color:#ffffff;border:1px solid #1e40af;">
                            + Nouvelle tache
                        </a>
                    </div>
                    <form method="GET" action="{{ route('tasks.index') }}"
                        class="mb-4 flex flex-wrap gap-3 items-end">
                        <div>
                            <label for="status" class="block text-sm text-gray-700">Statut</label>
                            <select id="status" name="status" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">Tous</option>
                                <option value="todo" @selected($statusFilter === 'todo')>A faire</option>
                                <option value="in_progress" @selected($statusFilter === 'in_progress')>En cours</option>
                                <option value="done" @selected($statusFilter === 'done')>Terminee</option>
                            </select>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm text-gray-700">Categorie</label>
                            <select id="category_id" name="category_id" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">Toutes</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $categoryFilter == $category->id ? 'selected' : '' }}>

                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50">
                            Filtrer
                        </button>

                        <a href="{{ route('tasks.index') }}"
                            class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50">
                            Reinitialiser
                        </a>
                    </form>


                    @if ($tasks->isEmpty())
                        <p>Aucune tache pour le moment.</p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Titre</th>
                                    <th class="text-left py-2">Categorie</th>
                                    <th class="text-left py-2">Statut</th>
                                    <th class="text-left py-2">Creee le</th>
                                    <th class="text-left py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $task->title }}</td>
                                        <td class="py-2">{{ $task->category->name }}</td>
                                        <td class="py-2">
                                            @if ($task->status === 'todo')
                                                A faire
                                            @elseif ($task->status === 'in_progress')
                                                En cours
                                            @else
                                                Terminee
                                            @endif
                                        </td>

                                        <td class="py-2">{{ $task->created_at->format('Y-m-d') }}</td>
                                        <td class="py-2">
                                            <div class="flex gap-2">
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                    class="px-3 py-1 rounded border border-blue-300 text-blue-700">
                                                    Modifier
                                                </a>

                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                                    onsubmit="return confirm('Supprimer cette tache ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 rounded border border-red-300 text-red-700">
                                                        Supprimer
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('tasks.status', $task) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="px-3 py-1 rounded border border-gray-300 text-gray-700">
                                                        Changer statut
                                                    </button>
                                                </form>

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
