<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CommandeBurger extends Pivot
{
    use HasFactory;

    protected $fillable = ['commande_id', 'burger_id', 'quantite'];
}

