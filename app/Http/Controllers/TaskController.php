<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
     $statusFilter = $request->query('status');
     $categoryFilter = $request->query('categories', []);

        $tasks = Task::with('category')
            ->where('user_id', $request->user()->id)
            ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
            ->when($categoryFilter, fn ($query) => $query->whereIn('category_id', $categoryFilter))
            ->latest()
            ->paginate(8)
            ->withQueryString();

            $categories = Category::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'categories', 'statusFilter', 'categoryFilter'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
           'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'category_id' => ['required', 'exists:categories,id'],
        'status' => ['nullable', 'in:todo,in_progress,done'],
        'due_date' => ['nullable', 'date'],

        ]);

        Task::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'category_id' => $validated['category_id'],
        'status' => $validated['status'] ?? 'todo',
        'due_date' => $validated['due_date'] ?? null,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tache creee avec succes.');
    }


    public function edit(Request $request, Task $task): View
    {

       if ($task->user_id !== $request->user()->id) {
            abort(403);
        }
    
        $categories = Category::orderBy('name')->get();
        return view('tasks.edit', compact('task', 'categories'));
    }



    public function update(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:todo,in_progress,done'],
            'due_date' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Tache mise a jour avec succes.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tache supprimee avec succes.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403);
        }

        $nextStatus = match ($task->status) {
            'todo' => 'in_progress',
            'in_progress' => 'done',
            'done' => 'todo',
            default => 'todo',
        };
        $task->update(['status' => $nextStatus]);

        return redirect()->route('tasks.index')->with('success', 'Statut de la tache mis a jour avec succes.');
    }
}

