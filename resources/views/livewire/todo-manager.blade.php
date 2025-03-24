<?php

use Livewire\Volt\Component;
use App\models\Todo;

new class extends Component {
    public Todo $todo;
    public string $todoName = '';

    public function with() {
        return [
            'todos' => Auth::user()->todos()->get()
    ];
    }

    public function createTodo() {
        $this->validate([
            'todoName' => 'required|string|max:255'
        ]);

        Auth::user()->todos()->create([
            'name' => $this->pull('todoName')
        ]);

        $this->todoName = '';
    }

    public function delete($id) {
        $todo = Auth::user()->todos()->find($id);
        $this->authorize('delete', $todo);
        $todo->delete();
    }
}; ?>

<div>
    <form wire:submit="createTodo" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="todoName"
            label="Title"
            type="text"
            required
            autofocus
            autocomplete="name"
            placeholder="Todo title"
        />
        <flux:button type="submit" variant="primary" class="w-full">
               Create
        </flux:button>
    </form>
    @foreach ($todos as $todo)
        <div class="flex items pt-4" wire:key="{{ $todo->id }}">
            <div class="flex-1">
                <p>{{ $todo->name }}</p>
            </div>
            <div>
                <flux:button variant='danger' wire:click="delete({{ $todo->id }})">Delete</flux:button>
            </div>
        </div>
    @endforeach
</div>
