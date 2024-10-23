<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Pays;



class Ville extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'intitule',
        'id_pays',
    ];

    public function id_pays(){
        return $this->belongsTo(Pays::class, 'id_pays');
    }
}
