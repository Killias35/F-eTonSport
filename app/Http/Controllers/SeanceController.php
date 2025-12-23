<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\ActiviteSeance;
use App\Models\Activite;
use Illuminate\Support\Str;

class SeanceController extends Controller
{

    public function index(Request $request)
    {
        return view('seances.index');
    }

    public function coach(Request $request)
    {
        $user = $request->user();
        if ($user->coach == null) {
            return Redirect()->route('seances.index');
        }
        $seances = Seance::where('user_id', $user->coach->id)->get();
        foreach ($seances as $seance) {
            $seance->done = $user->hasSeanceDone($seance->id);
        }
        return view('seances.coach', compact('seances'));
    }

    public function mines(Request $request)
    {
        $user_id = $request->user()->id;
        $seances = Seance::where('user_id', $user_id)->get();
        return view('seances.mines', compact('seances'));
    }
    
    public function create()
    {
        return view('seances.create');
    }

    public function createSpecial()
    {
        $activites = Activite::select('id', 'nom')->get();
        return view('seances.createSpecial', compact('activites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'exercises' => 'required',
        ]);

        $user_id = $request->user()->id;
        $titre = $request->input('titre');
        $image = $request->input('image');
        $description = $request->input('description');
        $exercises = $request->input('exercises');
        $exercises = json_decode($exercises, true);

        $seance = Seance::create([
            'titre' => $titre,
            'description' => $description,
            'image' => $image,
            'user_id' => $user_id
        ]);

        foreach ($exercises as $exercise) {
            ActiviteSeance::create([
                'seance_id' => $seance->id,
                'activite_id' => $exercise['id'],
                'quantity' => $exercise['quantity'],
                'difficulty' => $exercise['difficulty'],
                'poids' => $exercise['poids']
            ]);
        }

        // Récupérer toutes les activités liées à la séance
        $activiteSeances = $seance->activites()->get(); // Assure-toi d’avoir la relation 'activites' sur Seance

        // Remplacer les placeholders {{e1}}, {{e2}}, etc.
        $updatedDescription = $description;

        foreach ($exercises as $key => $exercise) {
            $activite = $activiteSeances->firstWhere('activite_id', $exercise['id']);
            if ($activite) {
                // Exemple de rendu : "Squat · 10 reps · diff 3"
                $nomActivite = $activite->nom ?? Activite::find($exercise['id'])->nom;
                $text = "{$nomActivite} · {$exercise['quantity']} reps · diff {$exercise['difficulty']}";
                $updatedDescription = Str::replace("{{{$key}}}", $text, $updatedDescription);
            }
        }

        // Mettre à jour la séance
        $seance->update([
            'description' => $updatedDescription
        ]);

        return Redirect()->route('seances.index');
    }

    public function edit(Request $request, $id)
    {
        $user_id = $request->user()->id;
        $seance = Seance::where('id', $id)->first();
        $canEdit = $seance->user_id == $user_id;

        return view('seances.edit', compact('seance', 'canEdit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required',
            'description' => 'required',
        ]);

        $titre = $request->input('titre');
        $description = $request->input('description');
        $image = $request->input('image');
        $user_id = $request->user()->id;

        $seance = Seance::where('id', $id)->first();
        if ($seance->user_id != $user_id) {  // ne peut pas updater une seance dont il n'est pas le proprietaire
            return Redirect()->route('seances.index');
        }
        $seance->update([
            'titre' => $titre,
            'description' => $description,
            'image' => $image,
        ]);
        return Redirect()->route('seances.index');
    }

    public function done(Request $request)
    {
        $request->validate([
            'seance_id' => 'required',
        ]);

        $seance_id = $request->input('seance_id');
        auth()->user()->doneSeances()->toggle($seance_id);
        return back();
    }

    public function destroy(Request $request, $id)
    {
        $user_id = $request->user()->id;
        $seance = Seance::where('id', $id)->first();
        if ($seance->user_id != $user_id) {  // ne peut pas supprimer une seance dont il n'est pas le proprietaire
            return Redirect()->route('seances.index');
        }
        $seance->delete();
        return Redirect()->route('seances.index');
    }
}
