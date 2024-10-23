<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Ville;



class Pays extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'intitule',
    ];

    public function villes(){
        return $this->hasMany(Ville::class, 'id_pays');
    }
}
