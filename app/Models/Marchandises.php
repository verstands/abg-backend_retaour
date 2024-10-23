<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Client;



class Marchandises extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'id_client',
        'produit',
        'qte',
    ];

    public function id_client(){
        return $this->belongsTo(Client::class, 'id_client');
    }
}
