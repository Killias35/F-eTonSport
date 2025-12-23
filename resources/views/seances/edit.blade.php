<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white-800 leading-tight">
            @if ($canEdit)
                Modifier la séance            
            @else
                Voir la séance
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/90 backdrop-blur shadow-xl sm:rounded-2xl border border-gray-700 p-8">
                <form method="POST" action="{{ route('seances.update', $seance->id) }}">
                    @method('PUT')
                    @csrf

                    @php
                        $disabled = $canEdit ? '' : 'disabled';
                        $color = $canEdit ? 'text-white' : 'text-gray-400';
                    @endphp

                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $seance->id }}"></input>

                    <div class="mb-6">
                        <label for="titre" class="block text-gray-300 font-semibold mb-2">Titre</label>
                        <textarea 
                            id="titre" 
                            name="titre" 
                            rows="1"
                            placeholder="WOD 15/12/2025" 
                            maxlength="200"
                            {{ $disabled }}
                            class="w-full p-4 bg-gray-800 {{ $color }} rounded-xl border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-lg leading-relaxed resize-none shadow-inner"
                        >{{ $seance->titre }}</textarea>

                        <label for="description" class="block text-gray-300 font-semibold mb-2">Décris ta séance</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="12"
                            placeholder="Écris ici ta séance du jour..." 
                            maxlength="800"
                            {{ $disabled }}
                            class="w-full p-4 bg-gray-800 {{ $color }} rounded-xl border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-lg leading-relaxed resize-none shadow-inner"
                        >{{ $seance->description }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2 text-red-400" />
                    </div>

                    <div class="flex justify-end">
                        @if ($canEdit)
                            <button class="px-6 py-2 rounded-lg bg-red-600 hover:bg-red-700 {{ $color }} font-semibold">
                                Enregistrer
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
