<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\TypeVisa;


class Visa extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'numero',
        'nom',
        'postnom',
        'prenm',
        'datenaissance',
        'nationalite',
        'sexe',
        'passeport',
        'adresse',
        'telephone',
        'etat',
        'id_typevisa'
    ];

    public function id_typevisa(){
        return $this->belongsTo(TypeVisa::class, 'id_typevisa');
    }

}
