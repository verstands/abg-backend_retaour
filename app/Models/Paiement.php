<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Paiement extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'montant',
        'id_client',
    ];
}
