<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class DepenseVisa extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'numero',
        'nom',
        'montant',
        'motif',
    ];
}
