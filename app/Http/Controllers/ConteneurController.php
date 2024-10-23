<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conteneur;
use Illuminate\Http\Response;
use App\Http\Requests\ConteneurFromRquest;
use App\Models\Client;

class ConteneurController extends Controller
{
    public function index(){
        $book = Conteneur::orderBy('created_at','desc')
            //->with('id_client')
            ->get();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(ConteneurFromRquest $request)
    {
        $validate = Conteneur::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        $book = Conteneur::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(ConteneurFromRquest $request, string $id){
        $book = Conteneur::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function groupageuser($id){
        $conteneur = Conteneur::where('id', $id)->first();
    
        if (!$conteneur) {
            return response()->json([
                'message' => 'Conteneur non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }
        $clients = Client::where('id_conteneur', $id)->with('marchandise')->get();
        $dataClients = [];
        foreach ($clients as $client) {
            $dataClients[] = [
                'client' => $client,
                'marchandises' => $client->marchandise
            ];
        }
    
        return response()->json([
            'data' => $conteneur,
            'dataclient' => $dataClients
        ], Response::HTTP_OK);
    }
    
    public function destroy(string $id){
        $depense = Conteneur::find($id);
        $depense->delete();

        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
    
}
