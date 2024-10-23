<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use Illuminate\Http\Response;
use App\Http\Requests\PaiementFromRquest;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaiementController extends Controller
{
    public function index(){
        $book = Paiement::orderBy('created_at','desc')
            //->with('type_id')
            //->with('type_id.group')
            //->with('user_id')
            //->with('status_id')
            ->paginate(10);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(PaiementFromRquest $request)
    {
        $client = Client::find($request->id_client);

        if (!$client) {
            return response()->json([
                'message' => "Client non trouvé"
            ], Response::HTTP_NOT_FOUND);
        }

        $nouveauMontant = $client->montantpayer + $request->montant;
        if($client->etat == 0){
            if($client->montantpayer < $client->montant){
                $validate = Paiement::create($request->validated());
                $client->update([
                    "montantpayer" => $nouveauMontant
                ]);
            } else {
                return response()->json([
                    'message' => "Le montant payé est déjà complet"
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'message' => "Votre montant est complet"
            ], Response::HTTP_BAD_REQUEST);
        }
       
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        try {
            $book = Paiement::where('id_client', $id)->get();
            return response()->json([
                'data' => $book
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Paiement non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    

    
    public function update(PaiementFromRquest $request, string $id){
        $book = Paiement::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $depense = Paiement::find($id);
        $depense->delete();
        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
}
