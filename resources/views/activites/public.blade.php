<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-white">
                Les activités public
            </h2>

            <a href="{{ route('activites.create') }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white font-semibold transition">
                + Créer une activité
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($activites as $activite)
                    <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-lg overflow-hidden flex flex-col">
                        @if ($activite->image)
                            <img src="{{ $activite->image }}"
                                 class="h-40 w-full object-cover">
                        @endif

                        <div class="p-4 flex-1">
                            <h3 class="text-lg font-bold text-white">
                                {{ $activite->nom }}
                            </h3>

                            <p class="mt-2 text-sm text-gray-400 line-clamp-3">
                                {{ $activite->description }}
                            </p>

                            
                        </div>
                        <div class="flex items-center p-4">
                            <p class="text-sm text-gray-200 line-clamp-3">
                                Créée par <span>"{{ $activite->user->name }}"</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-3 p-4 border-t border-gray-700">

                            <!-- Voir activité -->
                            <a href="{{ route('activites.edit', $activite->id) }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 py-2 rounded-xl
                                    bg-indigo-600 hover:bg-indigo-500 transition
                                    text-white text-sm font-semibold shadow">
                                👁️ Voir
                            </a>

                            <!-- Favori -->
                            <form method="POST" action="{{ route('activites.favorite') }}">
                                @csrf
                                <input type="hidden" name="activite_id" value="{{ $activite->id }}">

                                <button
                                    type="submit"
                                    class="w-12 h-12 flex items-center justify-center rounded-xl
                                        transition shadow
                                        {{ $activite->favorited
                                                ? 'bg-red-600 hover:bg-red-500'
                                                : 'bg-gray-700 hover:bg-gray-600' }}">
                                    <span class="text-xl">
                                        {{ $activite->favorited ? '❤️' : '🤍' }}
                                    </span>
                                </button>
                            </form>

                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
