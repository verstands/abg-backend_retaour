<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Ville;



class DepenseClient extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'motif',
        'montant',
        'id_client',
        'nom'
    ];

    public function id_client()
    {
        return $this->belongsTo(Ville::class, 'id_client');
    }
}
