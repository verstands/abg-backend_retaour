<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;



class Conteneur extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'nom_conteneur',
        'numero',
        'date_creation'
    ];

    public function clients() {
        return $this->hasMany(Client::class, 'id_conteneur', 'id');
    }
}
