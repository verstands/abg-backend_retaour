<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Conteneur;
use App\Models\Marchandises;


class Client extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'nom_client',
        'telephone',
        'montant',
        'id_conteneur',
        'etat',
        'montantpayer',
    ];

    public function id_conteneur(){
        return $this->belongsTo(Conteneur::class,'id_conteneur');
    }

    public function marchandise() {
        return $this->hasMany(Marchandises::class, 'id_client', 'id');
    }
}
