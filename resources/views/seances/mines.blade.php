<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-white-800 leading-tight">
                Toutes les séances
            </h2>
            <a href="{{ route('seances.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow transition">
                💪 Créer une séance
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(count($seances) === 0)
                <p class="text-white-400 text-center text-lg mt-8">Aucune séance n’a encore été créée.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($seances as $seance)
                        <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-lg overflow-hidden flex flex-col">
                            <div class="p-4 flex-1">
                                <h3 class="text-lg font-bold text-white">
                                    {{ $seance->titre }}
                                </h3>

                                <p class="mt-2 text-sm text-gray-400 line-clamp-3 description-{{ $seance->id }}">
                                    {{ $seance->description }}
                                </p>
                            </div>

                            <div class="flex gap-2 p-4 border-t border-gray-700">
                                <a href="{{ route('seances.edit', $seance->id) }}"
                                class="flex-1 text-center py-2 rounded-lg bg-gray-700 hover:bg-gray-600 text-white text-sm">
                                    Modifier
                                </a>

                                <form method="POST" action="{{ route('seances.destroy', $seance->id) }}" class="flex-1" onsubmit="return confirm('Voulez-vous vraiment supprimer cette exercice ?');">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="w-full py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        const seances = @json($seances);

        seances.forEach(seance => {
            const description = document.querySelector(`.description-${seance.id}`);
            seance.exercises.forEach(exercise => {
                description.innerHTML = description.innerHTML.replace(
                    `@{{${exercise.id}}}`, 
                    `<span data-key="${exercise.id}">${exercise.nom} · ${exercise.quantity} · ${exercise.difficulty} ·${exercise.poids} </span>`);
                    console.log(`@{{${exercise.id}}}`);
                });

        })
    </script>
</x-app-layout>
