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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" action="{{ route('tickets.index') }}">
                <x-text-input name="q" type="search" class="w-full" placeholder="Buscar por asunto" :value="request('q')" />
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Asunto</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Prioridad</th>
                            <th class="px-6 py-3">Actualizado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900">{{ $ticket->subject }}</a>
                                </td>
                                <td class="px-6 py-4"><x-ticket-status :status="$ticket->status" /></td>
                                <td class="px-6 py-4">{{ $ticket->priority?->label() ?? 'Sin clasificar' }}</td>
                                <td class="px-6 py-4">{{ $ticket->updated_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay tickets.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $tickets->links() }}
        </div>
    </div>
</x-app-layout>
