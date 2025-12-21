<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;
use Illuminate\Support\Facades\Redirect;

class ActiviteController extends Controller
{
    public function index()
    {
        return view('activites.index');
    }

    public function mines(Request $request)
    {
        $user_id = $request->user()->id;
        $activites = Activite::where('user_id', $user_id)->get();

        return view('activites.mines', compact('activites'));
    }

    public function create()
    {
        return view('activites.create');
    }

    public function public(Request $request)
    {
        $activites = Activite::all();
        $user = $request->user();
        foreach ($activites as $activite) {
            $activite->favorited = $user->hasFavoriteActivite($activite->id);
        }

        return view('activites.public', compact('activites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'description' => 'required',
            'user_id' => 'required',
        ]);

        $nom = $request->input('nom');
        $description = $request->input('description');
        $image = $request->input('image');
        $user_id = $request->input('user_id');

        Activite::create([
            'nom' => $nom,
            'description' => $description,
            'image' => $image,
            'user_id' => $user_id,
        ]);

        return Redirect()->route('activites.index');
    }

    public function favorite(Request $request)
    {
        $request->validate([
            'activite_id' => 'required',
        ]);

        $activite_id = $request->input('activite_id');

        auth()->user()->activitesFavorites()->toggle($activite_id);
        return back();
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $activite = Activite::where('id', $id)->first();
        $canEdit = $activite->user_id == $user->id;
        return view('activites.edit', compact('activite', 'canEdit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required',
            'description' => 'required',
        ]);

        $activite = Activite::where('id', $id)->first();
        $user = $request->user();

        if ($activite->user_id != $user->id) {  // ne peut pas updater une activite dont il n'est pas le proprietaire
            return Redirect()->route('activites.index');
        }

        $nom = $request->input('nom');
        $description = $request->input('description');
        $image = $request->input('image');

        $activite->update([
            'nom' => $nom,
            'description' => $description,
            'image' => $image,
        ]);

        return Redirect()->route('activites.index');
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $activite = Activite::where('id', $id)->first();
        if ($activite->user_id != $user->id) {  // ne peut pas supprimer une activite dont il n'est pas le proprietaire
            return Redirect()->route('activites.index');
        }
        $activite->delete();

        return Redirect()->route('activites.index');
    }
}
