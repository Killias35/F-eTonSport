<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white-800 leading-tight">
            Statistiques de l'utilisateur
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/90 backdrop-blur shadow-xl sm:rounded-2xl border border-gray-700 p-8">
                
                {{-- Coach --}}
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white mb-2">Coach :</h3>
                    <p class="text-gray-300">{{ $user->coach ? $user->coach->name : 'Aucun coach' }}</p>
                </div>

                {{-- Séances effectuées --}}
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white mb-2">Nombre de séances de coaching effectuées :</h3>
                    <p class="text-gray-300">{{ $user->doneSeances->count() }}</p>

                    <h3 class="text-lg font-bold text-white mb-2">Nombre de séances perso effectuées :</h3>
                    <p class="text-gray-300">{{ $user->seances->count() }}</p>
                </div>

                {{-- Activités --}}
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white mb-2">Activités effectuées :</h3>
                    @php
                        $activites = collect();
                        // Activités des séances effectuées
                        foreach ($user->doneSeances as $seance) {
                            if ($seance->activites != null) {
                                $activites = $activites->merge($seance->activites->pluck('activite.nom'));
                            }
                        }
                        // Activités des séances créées par l'utilisateur
                        foreach ($user->seances as $seance) {
                            if ($seance->activites != null) {
                                $activites = $activites->merge($seance->activites->pluck('activite.nom'));
                            }
                        }
                        $activites = $activites->unique();
                    @endphp

                    @if($activites->isEmpty())
                        <p class="text-gray-400">Aucune activité effectuée pour l'instant.</p>
                    @else
                        <ul class="list-disc list-inside text-gray-300">
                            @foreach($activites as $act)
                                <li>{{ $act }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Exemple de graphique simple (demo) --}}
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white mb-2">Répartition des activités (demo)</h3>
                    <div class="w-full h-64 bg-gray-800 rounded-xl flex items-center justify-center text-gray-400">
                        Graphique placeholder
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
