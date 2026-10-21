<div class="space-y-4">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <x-text-input wire:model.live="search" type="search" class="w-full" placeholder="Buscar por asunto" />

        <label class="flex shrink-0 items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" wire:model.live="onlyOpen" class="rounded border-gray-300 text-indigo-600">
            Solo sin cerrar
        </label>
    </div>

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
                @forelse ($this->tickets as $ticket)
                    <tr wire:key="{{ $ticket->id }}">
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

    {{ $this->tickets->links() }}
</div>
