<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\ActiviteSeance;
use App\Models\Activite;
use Illuminate\Support\Str;

use App\Http\Services\SeanceService;

class SeanceController extends Controller
{

    public function index(Request $request)
    {
        return view('seances.index');
    }

    public function coach(Request $request)
    {
        $user = $request->user();
        $seances = SeanceService::seancesCoach($user);

        if ($seances == null) {
            return Redirect()->route('seances.index');
        }
        return view('seances.coach', compact('seances'));
    }

    public function mines(Request $request)
    {
        $user = $request->user();
        $seances = SeanceService::seancesMines($user);
        return view('seances.mines', compact('seances'));
    }
    
    public function create()
    {
        $activites = SeanceService::getActivites();
        return view('seances.create', compact('activites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'exercises' => 'required',
        ]);

        $user = $request->user();
        $titre = $request->input('titre');
        $image = $request->input('image');
        $description = $request->input('description');
        $exercises = $request->input('exercises');
        $exercises = json_decode($exercises, true);

        SeanceService::create($user, $titre, $image, $description, $exercises);

        return Redirect()->route('seances.index');
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $seance = SeanceService::get($id);
        $canEdit = $seance->user_id == $user->id;

        return view('seances.edit', compact('seance', 'canEdit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required',
            'description' => 'required',
        ]);

        $user = $request->user();
        $titre = $request->input('titre');
        $description = $request->input('description');
        $image = $request->input('image');

        $seance = SeanceService::get($id);
        if ($seance->user_id == $user->id) {  // ne peut pas updater une seance dont il n'est pas le proprietaire
            SeanceService::update($id, $titre, $description, $image);
        }
        return Redirect()->route('seances.index');
    }

    public function done(Request $request)
    {
        $request->validate([
            'seance_id' => 'required',
        ]);

        $user = $request->user();
        $seance_id = $request->input('seance_id');
        
        SeanceService::done($user, $seance_id);
        
        return back();
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $seance = SeanceService::get($id);

        if ($seance->user_id != $user->id) {  // ne peut pas supprimer une seance dont il n'est pas le proprietaire
            return Redirect()->route('seances.index');
        }
        
        SeanceService::destroy($id);
        return Redirect()->route('seances.index');
    }
}
