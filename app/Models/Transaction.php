<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Ville;

class Transaction extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'nom_emateur',
        'nom_recepteur',
        'matricule',
        'telephone',
        'pays_provenance',
        'pays_destinateut',
        'montant',
        'motif',
        'etat',
    ];

    public function pays_provenance()
    {
        return $this->belongsTo(Ville::class, 'pays_provenance');
    }
    public function pays_destinateut()
    {
        return $this->belongsTo(Ville::class, 'pays_destinateut');
    }
}
