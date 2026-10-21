<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo ticket
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="subject" value="Asunto" />
                        <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" :value="old('subject')" required />
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="body" value="Descripción" />
                        <textarea id="body" name="body" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('body') }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="priority" value="Prioridad" />
                        <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->value }}" @selected(old('priority', 'normal') === $priority->value)>{{ $priority->label() }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="attachments" value="Adjuntos" />
                        <input id="attachments" name="attachments[]" type="file" multiple class="mt-1 block w-full text-sm">
                        <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
                    </div>

                    <x-primary-button>Abrir ticket</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
