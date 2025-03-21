<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'statut','montantTotal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function burgers()
    {
        return $this->belongsToMany(Burger::class, 'commandes_burgers')->withPivot('quantite');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}
