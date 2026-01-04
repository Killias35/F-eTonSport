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
                <form method="POST" action="{{ route('seances.update', $seance->id) }}" id="seance-form">
                    @csrf
                    @method('PUT')

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
                            @if ($canEdit)
                                Décris ta séance (utilise # pour ajouter un exercice)
                            @else
                                Description de la séance
                            @endif
                        </label>

                        <div
                            id="editor"
                            contenteditable="true"
                            class="w-full min-h-[300px] p-4 bg-gray-800 text-white rounded-xl border border-gray-700 focus:ring-red-500 text-lg leading-relaxed shadow-inner"
                        >{!! $seance->description !!}
                        </div>

                        {{-- HIDDEN INPUTS --}}
                        <input type="hidden" name="description" id="description">
                        <input type="hidden" name="exercises" id="exercises">
                    </div>

                    @if ($canEdit)
                        <div class="flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transition">
                                Enregistrer la séance
                            </button>
                        </div>
                    @endif
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
const seanceExercises = @json($seance->exercises);

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

editor.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault(); // empêche le div automatique

        const range = window.getSelection().getRangeAt(0);

        const br = document.createElement('br'); // ou un span vide si tu veux
        range.insertNode(br);

        // Avancer le curseur après le br
        range.setStartAfter(br);
        range.setEndAfter(br);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    }
});

editor.addEventListener('keyup', () => {
    const selection = window.getSelection();
    if (!selection.rangeCount) return;

    const text = editor.innerText;
    const match = text.match(/#(\w*)/);

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

function insertExercise(activity, replace=true, getSpan=false) {
    const key = state.counter;
    state.counter++;
    
    const id = activity.activite_id ?? activity.id;
    const nom = activity.nom;
    const quantity = activity.quantity ?? '10x4';
    const difficulty = activity.difficulty ?? 'rpe 5';
    const poids = activity.poids ?? '';

    state.exercises[key] = {
        id: id,
        quantity: quantity,
        difficulty: difficulty,
        poids: poids
    };

    if (replace) {
        const matches = [...editor.innerText.matchAll(/#(\w*)/g)];
        let pos = 0;
        matches.forEach(found => {
            pos = editor.innerHTML.indexOf(found[0]);
            editor.innerHTML = editor.innerHTML.replace(found[0], '');
        });
    }

    const span = document.createElement('span');
    span.contentEditable = false;
    span.dataset.key = key;
    span.className =
        'inline-flex items-center px-3 py-1 mx-1 rounded-lg bg-red-600 text-white text-sm cursor-pointer select-none';

    span.textContent = ` ${nom} · ${quantity} · ${difficulty} · ${poids} `;
    autocomplete.classList.add('hidden');
    
    if (getSpan) return span;
    editor.appendChild(span);
    spanClick();
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
    span.textContent = ` ${activity.nom} · ${data.quantity} · ${data.difficulty} · ${data.poids} `;
}

function spanClick(){
    spans = document.getElementsByClassName('inline-flex items-center px-3 py-1 mx-1 rounded-lg bg-red-600 text-white text-sm cursor-pointer select-none');
    for (let i = 0; i < spans.length; i++) {
        spans[i].addEventListener('click', () => editExercise(spans[i]));
    }
}

form.addEventListener('submit', () => {
    let content = editor.innerHTML.replace(/&nbsp;/g, ' ');

    Object.keys(state.exercises).forEach(key => {
        const regex = new RegExp(
            `<span[^>]*data-key="${key}"[^>]*>.*?<\\/span>`,
            'g'
        );
        content = content.replace(regex, `@{{${key}}}`);
    });

    document.getElementById('description').value = content;
    document.getElementById('exercises').value = JSON.stringify(state.exercises);
});

function hydrateEditor() {
    const fullDescription = editor.innerHTML;
    editor.innerHTML = '';
    let description = '';
    let index = 0;

    seanceExercises.forEach(exercise => {
        let text = fullDescription.substring(index, fullDescription.indexOf(`@{{${exercise.id}}}`));
        description += text;
        index += `@{{${exercise.id}}}`.length + text.length;
        span = insertExercise(exercise, false, true);
        description += span.outerHTML;
    });

    description += fullDescription.substring(index);
    editor.innerHTML = description;
    spanClick();
}

hydrateEditor();

</script>
</x-app-layout>