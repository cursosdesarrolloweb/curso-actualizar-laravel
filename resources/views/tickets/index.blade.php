<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tickets
            </h2>
            <a href="{{ route('tickets.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Nuevo ticket</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:ticket-list :search="$search" />
        </div>
    </div>
</x-app-layout>
