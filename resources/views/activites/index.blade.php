<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-white-900 leading-tight">
            Activités
        </h2>
        <p class="text-sm text-gray-400 mt-1">
            Gère et consulte des exercices
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Activités publiques -->
                <a href="{{ route('activites.public') }}"
                   class="group relative overflow-hidden rounded-3xl p-8
                          bg-gradient-to-br from-indigo-500 to-purple-600
                          text-white shadow-lg hover:shadow-2xl transition">

                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition"></div>

                    <div class="relative z-10 text-center">
                        <div class="text-6xl mb-4">🌍</div>
                        <h3 class="text-2xl font-bold mb-2">
                            Activités publiques
                        </h3>
                        <p class="text-sm text-indigo-100">
                            Découvre les exercices partagés par la communauté
                        </p>
                    </div>
                </a>

                <!-- Activités créées -->
                <a href="{{ route('activites.mines') }}"
                   class="group relative overflow-hidden rounded-3xl p-8
                          bg-gradient-to-br from-emerald-500 to-teal-600
                          text-white shadow-lg hover:shadow-2xl transition">

                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition"></div>

                    <div class="relative z-10 text-center">
                        <div class="text-6xl mb-4">🏋️</div>
                        <h3 class="text-2xl font-bold mb-2">
                            Mes activités
                        </h3>
                        <p class="text-sm text-emerald-100">
                            Consulte et modifie les exercices que tu as créés
                        </p>
                    </div>
                </a>

                <!-- Activités réalisées (future feature) -->
                <div
                    class="relative overflow-hidden rounded-3xl p-8
                           bg-gradient-to-br from-gray-200 to-gray-300
                           text-gray-600 shadow-inner cursor-not-allowed">

                    <div class="absolute inset-0 bg-white/40"></div>

                    <div class="relative z-10 text-center">
                        <div class="text-6xl mb-4 opacity-70">📊</div>
                        <h3 class="text-2xl font-bold mb-2">
                            Activités réalisées
                        </h3>
                        <p class="text-sm text-gray-500">
                            Bientôt disponible
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
