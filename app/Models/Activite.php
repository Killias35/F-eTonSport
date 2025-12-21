<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    use HasFactory;

    /**
     * Champs assignables en masse
     */
    protected $fillable = [
        'nom',
        'image',
        'description',
        'user_id',
    ];

    /**
     * Relation : une activité appartient à un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usersWhoFavorited()
    {
        return $this->belongsToMany(
            User::class,
            'activite_user_favorite'
        )->withTimestamps();
    }
}
