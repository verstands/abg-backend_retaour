<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Cloture extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'entredubai',
        'sortidubai',
        'entreKinhsasa',
        'sortiKinhsasa',
        'depenseDubai',
        'depenseKinshasa',
        'dettepartenaire',
        'detteclient',
        'balanceDubai',
        'balanceKinshasa'
    ];
}
