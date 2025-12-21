<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Activite;

class ActiviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_id = 1;
        $noms = [
            'Développé couché',
            'Squat',
            'Soulevé de terre',
            'Tractions',
            'Développé militaire',
            'Rowing haltère',
            'Gainage'
        ];

        $descriptions = [
            "Exercice polyarticulaire pour le haut du corps qui sollicite principalement les pectoraux, triceps et épaules.", // Développé couché
            "Mouvement de base pour le bas du corps, engageant quadriceps, fessiers et les muscles stabilisateurs.", // Squat
            "Exercice complet de force sollicitant le dos, les fessiers et les ischio-jambiers.", // Soulevé de terre
            "Exercice de tirage au poids du corps principalement pour le dos et les biceps.", // Tractions
            "Développé debout pour travailler les épaules, en mettant l’accent sur les deltoïdes.", // Développé militaire
            "Tirage unilatéral avec haltère ciblant le dos, particulièrement le grand dorsal.", // Rowing haltère
            "Exercice de renforcement du tronc en position statique, excellent pour les abdominaux et la sangle.", // Gainage
        ];

        $images = [
            "https://www.docteur-fitness.com/comment-faire-developpe-couche", // exemple Bench Press / développé couché 
            "https://images.unsplash.com/photo-1605296867304-46d5465a13f1", // exemple Squat free weights 
            "https://www.docteur-fitness.com/souleve-de-terre", // exemple Deadlift barbell 
            "https://www.fitnessmith.fr/progresser-aux-tractions-de-0-a-5-tractions/", // exemple Pull up bar 
            "https://www.setforset.com/blogs/news/overhead-press-how-to-muscles-worked-benefits-variations", // exemple Overhead press / militaire 
            "https://images.unsplash.com/photo-1599058917219-96f22902f217", // exemple Dumbbell row 
            "https://images.unsplash.com/photo-1583454110457-aec0f4e4e5e9", // exemple Plank / gainage 
        ];


        for ($i = 0; $i < count($noms); $i++) {
            Activite::create([
                'nom' => $noms[$i],
                'description' => $descriptions[$i],
                'image' => $images[$i],
                'user_id' => $user_id,
            ]);
        }
    }
}
