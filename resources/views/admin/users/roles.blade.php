<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Roles de {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.users.roles.update', $user) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    @foreach ($roles as $role)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded border-gray-300 text-indigo-600" @checked($user->hasRole($role))>
                            <span>{{ $role->name }}</span>
                        </label>
                    @endforeach

                    <x-input-error :messages="$errors->get('roles.*')" />

                    <x-primary-button>Guardar</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
