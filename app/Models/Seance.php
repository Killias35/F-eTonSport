<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    
    protected $fillable = [
        'titre',
        'description',
        'image',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activites()
    {
        return $this->hasMany(ActiviteSeance::class);
    }

    public function deleteAllActivitesOfSeance()
    {
        foreach ($this->activites as $activite) {
            $activite->delete();
        }
    }

}
