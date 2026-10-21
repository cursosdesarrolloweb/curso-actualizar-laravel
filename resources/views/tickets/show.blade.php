<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $ticket->subject }}
            </h2>
            <x-ticket-status :status="$ticket->status" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-auth-session-status :status="session('status')" />

            <article class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <dl class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
                    <div>
                        <dt class="text-gray-500">Cliente</dt>
                        <dd>{{ $ticket->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Prioridad</dt>
                        <dd>{{ $ticket->priority?->label() ?? 'Sin clasificar' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Vence</dt>
                        <dd>
                            {{ $ticket->due_at?->format('d/m/Y H:i') ?? '-' }}
                            @if ($ticket->hours_left !== null)
                                <span @class(['text-red-600' => $ticket->hours_left < 0, 'text-gray-500' => $ticket->hours_left >= 0])>({{ $ticket->hours_left }} h)</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Estimación</dt>
                        <dd>{{ $ticket->estimated_hours !== null ? $ticket->estimated_hours.' h' : '-' }}</dd>
                    </div>
                </dl>

                <div class="text-gray-800">{!! nl2br(e($ticket->body)) !!}</div>

                @if ($ticket->attachments->isNotEmpty())
                    <ul class="text-sm space-y-1">
                        @foreach ($ticket->attachments as $attachment)
                            <li>
                                <a href="{{ route('attachments.show', $attachment) }}" class="text-indigo-600 hover:text-indigo-900">{{ $attachment->original_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </article>

            @foreach ($replies as $reply)
                <article @class(['shadow-sm sm:rounded-lg p-6 space-y-2', 'bg-white' => ! $reply->is_internal, 'bg-amber-50' => $reply->is_internal])>
                    <p class="text-sm text-gray-500">
                        {{ $reply->user->name }} · {{ $reply->created_at->format('d/m/Y H:i') }}
                        @if ($reply->is_internal)
                            · Nota interna
                        @endif
                    </p>
                    <div class="text-gray-800">{!! nl2br(e($reply->body)) !!}</div>
                </article>
            @endforeach

            @unless ($ticket->isClosed())
                <form method="POST" action="{{ route('tickets.replies.store', $ticket) }}" enctype="multipart/form-data" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="body" value="Respuesta" />
                        <textarea id="body" name="body" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('body') }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="reply-attachments" value="Adjuntos" />
                        <input id="reply-attachments" name="attachments[]" type="file" multiple class="mt-1 block w-full text-sm">
                        <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
                    </div>

                    @can('tickets.view-any')
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-indigo-600">
                            Nota interna, el cliente no la ve
                        </label>
                    @endcan

                    <x-primary-button>Responder</x-primary-button>
                </form>
            @endunless

            @can('tickets.view-any')
                <form method="POST" action="{{ route('tickets.triage.update', $ticket) }}" class="bg-white shadow-sm sm:rounded-lg p-6 grid gap-4 sm:grid-cols-3 sm:items-end">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="triage-priority" value="Prioridad" />
                        <select id="triage-priority" name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Sin clasificar</option>
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->value }}" @selected($ticket->priority === $priority)>{{ $priority->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="estimated_hours" value="Horas estimadas" />
                        <x-text-input id="estimated_hours" name="estimated_hours" type="number" step="0.25" min="0" class="mt-1 block w-full" :value="$ticket->estimated_hours" />
                        <x-input-error :messages="$errors->get('estimated_hours')" class="mt-2" />
                    </div>

                    <x-primary-button class="justify-center">Clasificar</x-primary-button>
                </form>
            @endcan
        </div>
    </div>
</x-app-layout>
