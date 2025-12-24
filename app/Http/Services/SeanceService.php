<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\ActiviteSeance;
use App\Models\Activite;
use App\Models\User;
use Illuminate\Support\Str;

class SeanceService
{

    public static function index()
    {
        return Seance::all();
    }

    public static function get($id)
    {
        $seance = Seance::where('id', $id)->first();
        $seance = self::parseDescription($seance);
        return $seance;
    }

    public static function seancesCoach(User $user)
    {
        if ($user->coach == null) {
            return [];
        }
        $seances = Seance::where('user_id', $user->coach->id)->get();
        $seances = self::parseDescriptions($seances);

        foreach ($seances as $seance) {
            $seance->done = $user->hasSeanceDone($seance->id);
        }
        return $seances;
    }

    public static function seancesMines(User $user)
    {
        $user_id = $user->id;
        $seances = Seance::where('user_id', $user_id)->get();
        $seances = self::parseDescriptions($seances);
        return $seances;
    }

    public static function parseDescriptions(Collection $seances){
        $newSeances = [];
        foreach ($seances as $seance) {
            $newSeances[] = self::parseDescription($seance);
        }
        return $newSeances;
    }
    
    public static function parseDescription(Seance $seance){
        $exercises = [];
        foreach ($seance->activites as $activite) {
            if (str_contains($seance->description, "{{$activite->id}}")) {
                $activite->nom = $activite->activite->nom;
                $activite->activite_id = $activite->activite->id;
                $exercises[] = $activite;
            }
        }
        $seance->exercises = $exercises;
        return $seance;   
    }
    
    
    public static function getActivites()
    {
        $activites = Activite::select('id', 'nom')->get();
        return $activites;
    }

    public static function deleteAllActivitesOfSeance(Seance $seance)
    {
        $seance->deleteAllActivitesOfSeance();
    }

    public static function create(User $user, string $titre, string | null $image, string $description, array $exercises)
    {
        $user_id = $user->id;
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
            $index = intval($key);
            $activite = $activiteSeances[$index];
            if ($activite) {
                $text = $activite->id;
                $updatedDescription = Str::replace("{{{$key}}}", "{{{$text}}}", $updatedDescription);
            }
        }

        // Mettre à jour la séance
        $seance->update([
            'description' => $updatedDescription
        ]);

        return $seance;
    }

    public static function update(int $id, string $titre, string $description, array $exercises, string | null $image)
    {
        $seance = Seance::where('id', $id)->first();
        self::deleteAllActivitesOfSeance($seance);
        
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
            $index = intval($key);
            $activite = $activiteSeances[$index];
            if ($activite) {
                $text = $activite->id;
                $updatedDescription = Str::replace("{{{$key}}}", "{{{$text}}}", $updatedDescription);
            }
        }

        // Mettre à jour la séance
        $seance->update([
            'titre' => $titre,
            'image' => $image,
            'description' => $updatedDescription
        ]);

        return $seance;
    }

    public static function done(User $user, int $id)
    {
        if (contains($user->coach()->seances()->pluck('id')->toArray(), $id)) {     // si la seance appartient au coach
            $user->doneSeances()->toggle($id);
        }
    }

    public static function destroy($id)
    {
        Seance::where('id', $id)->first()->delete();
    }
}
