<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Transaction;



class DettePartenaire extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'intitule',
        'montant',
        'montantpayer',
        'etat',
        'transaction_id',
    ];

    public function transaction_id(){
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
