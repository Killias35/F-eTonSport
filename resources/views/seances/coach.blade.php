<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-white leading-tight">
                    Séances proposées par le coach
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    Programmes d’entraînement disponibles pour les athlètes
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(count($seances) === 0)
                <p class="text-gray-400 text-center text-lg mt-12">
                    Aucune séance n’est encore disponible pour le moment.
                </p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($seances as $seance)
                        <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-lg overflow-hidden flex flex-col hover:border-indigo-500 transition">
                            
                            <div class="p-5 flex-1">
                                <h3 class="text-lg font-bold text-white">
                                    {{ $seance->titre }}
                                </h3>

                                <p class="mt-3 text-sm text-gray-400 line-clamp-3">
                                    {{ $seance->description }}
                                </p>
                            </div>
                            <div class="flex gap-2 p-4 border-t border-gray-800">
                                <a href="{{ route('seances.edit', $seance->id) }}"
                                class="flex-1 text-center py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
                                    👁 Voir
                                </a>

                                <form method="POST"
                                    action="{{ route('seances.done') }}"
                                    class="flex-1">
                                    @csrf
                                    <input type="hidden" name="seance_id" value="{{ $seance->id }}">

                                    @if ($seance->done)
                                        <button
                                            class="w-full py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm">
                                            ✅ Fait
                                        </button>
                                    @else
                                        <button
                                            class="w-full py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
                                            ❌ Pas Fait
                                        </button>
                                    @endif
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
