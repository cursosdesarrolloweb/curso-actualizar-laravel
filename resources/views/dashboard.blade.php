<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($statuses as $status)
                    <a href="{{ route('tickets.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <x-ticket-status :status="$status" />
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats[$status->value] ?? 0 }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
