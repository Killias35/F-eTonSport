<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white-800 leading-tight">
            Nouvelle Séance
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/90 backdrop-blur shadow-xl sm:rounded-2xl border border-gray-700 p-8">
                <form method="POST" action="{{ route('seances.store') }}" id="seance-form">
                    @csrf

                    {{-- TITRE --}}
                    <div class="mb-6">
                        <label class="block text-gray-300 font-semibold mb-2">Titre</label>
                        <textarea
                            name="titre"
                            rows="1"
                            class="w-full p-4 bg-gray-800 text-white rounded-xl border border-gray-700 focus:ring-red-500 resize-none"
                        >Seance du {{ today()->format('d/m/Y') }}</textarea>
                    </div>

                    {{-- EDITEUR --}}
                    <div class="mb-6 relative">
                        <label class="block text-gray-300 font-semibold mb-2">
                            Décris ta séance (utilise # pour ajouter un exercice)
                        </label>

                        <div
                            id="editor"
                            contenteditable="true"
                            class="w-full min-h-[300px] p-4 bg-gray-800 text-white rounded-xl border border-gray-700 focus:ring-red-500 text-lg leading-relaxed shadow-inner"
                        ></div>

                        {{-- HIDDEN INPUTS --}}
                        <input type="hidden" name="description" id="description">
                        <input type="hidden" name="exercises" id="exercises" value="test">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transition">
                            Enregistrer la séance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- AUTOCOMPLETE --}}
    <div
        id="autocomplete"
        class="absolute z-50 hidden bg-gray-800 border border-gray-700 rounded-xl shadow-lg max-h-48 overflow-y-auto"
    ></div>


<script>
const activites = @json($activites);
let state = {
    counter: 0,
    exercises: {}
};

const editor = document.getElementById('editor');
const autocomplete = document.getElementById('autocomplete');
const form = document.getElementById('seance-form');

function getCaretPosition() {
    const selection = window.getSelection();
    if (!selection.rangeCount) return null;

    const range = selection.getRangeAt(0).cloneRange();
    range.collapse(false);

    const rects = range.getClientRects();
    return rects.length ? rects[0] : null;
}

editor.addEventListener('keyup', () => {
    const selection = window.getSelection();
    if (!selection.rangeCount) return;

    const text = editor.innerText;
    const match = text.match(/#(\w*)$/);

    if (!match) {
        autocomplete.classList.add('hidden');
        return;
    }

    const query = match[1].toLowerCase();
    const results = activites.filter(a =>
        a.nom.toLowerCase().includes(query)
    );

    if (!results.length) {
        autocomplete.classList.add('hidden');
        return;
    }

    autocomplete.innerHTML = '';
    results.forEach(a => {
        const item = document.createElement('div');
        item.className = 'px-4 py-2 hover:bg-gray-700 cursor-pointer text-white';
        item.textContent = a.nom;
        item.onclick = () => insertExercise(a);
        autocomplete.appendChild(item);
    });

    const caret = getCaretPosition();
    if (caret) {
        autocomplete.style.left = caret.left + 'px';
        autocomplete.style.top = (caret.bottom + window.scrollY) + 'px';
    }
    autocomplete.classList.remove('hidden');
});

function insertExercise(activity) {
    state.counter++;
    const key = 'e' + state.counter;

    state.exercises[key] = {
        id: activity.id,
        quantity: '10x4',
        difficulty: 'rpe 5',
        poids: ''
    };

    // Supprime le #mot tapé
    editor.innerHTML = editor.innerHTML.replace(/#\w*$/, '');

    const span = document.createElement('span');
    span.contentEditable = false;
    span.dataset.key = key;
    span.className =
        'inline-flex items-center px-3 py-1 mx-1 rounded-lg bg-red-600 text-white text-sm cursor-pointer select-none';

    span.textContent = `${activity.nom} · 10x4 · rpe 5 · `;
    span.addEventListener('click', () => editExercise(span));

    editor.appendChild(span);
    editor.appendChild(document.createTextNode(' '));

    autocomplete.classList.add('hidden');
}

function editExercise(span) {
    const key = span.dataset.key;
    const data = state.exercises[key];

    const quantity = prompt('Quantité', data.quantity);
    const difficulty = prompt('Difficulté', data.difficulty);
    const poids = prompt('Poids', data.poids);

    if (quantity !== null) data.quantity = quantity;
    if (difficulty !== null) data.difficulty = difficulty;
    if (poids !== null) data.poids = poids;

    const activity = activites.find(a => a.id === data.id);
    span.textContent = `${activity.nom} · ${data.reps} · ${data.difficulty} · ${data.poids}`;
    console.log(state.exercises);
}

form.addEventListener('submit', () => {
    let content = editor.innerHTML;

    Object.keys(state.exercises).forEach(key => {
        const regex = new RegExp(
            `<span[^>]*data-key="${key}"[^>]*>.*?<\\/span>`,
            'g'
        );
        content = content.replace(regex, `@{{${key}}}`);
    });

    document.getElementById('description').value = content;
    document.getElementById('exercises').value = JSON.stringify(state.exercises);
    console.log(document.getElementById('exercises').value);
});
</script>
</x-app-layout>