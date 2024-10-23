<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Transaction;


class Dette extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'motif_dette',
        'montant_dette',
        'etat_dette',
        'montantpayer',
        'id_transaction'
    ];

    public function id_transaction(){
        return $this->belongsTo(Transaction::class,'id_transaction');
    }
}
