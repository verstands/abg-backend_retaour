<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Conteneur;
use App\Models\TypeDepense;



class DepenseConteneur extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'id_conteneur',
        'montant',
        'id_typedepense'
    ];

    public function id_conteneur(){
        return $this->belongsTo(Conteneur::class, 'id_conteneur');
    }

    public function id_typedepense(){
        return $this->belongsTo(TypeDepense::class, 'id_typedepense');
    }
}
