<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientFromRquest;
use App\Http\Requests\MarchandiseRequest;
use App\Models\Client;
use App\Models\Conteneur;
use App\Models\Marchandises;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    public function clientConteneur($id)
    {
        try {
            $clients = Client::where('id_conteneur', $id)
                ->orderBy('created_at', 'desc')
                ->with('id_conteneur')
                ->get();

            if ($clients->isEmpty()) {
                return response()->json([
                    'message' => 'Aucun client trouvé pour ce conteneur.',
                    'data' => []
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Clients récupérés avec succès.',
                'data' => $clients
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des clients.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ClientFromRquest $request)
    {
        $clientData = $request->validated();
        $marchandisesData = $clientData['marchandises'];

        if (empty($marchandisesData)) {
            return response()->json([
                'message' => "Vous ne pouvez pas enregistrer un client sans marchandise"
            ], Response::HTTP_OK);
        }

        $client = Client::create($clientData);
        $clientId = $client->id;

        foreach ($marchandisesData as $marchandise) {
            $marchandise['id_client'] = $clientId;
            Marchandises::create($marchandise);
        }

        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }




    public function show(string $id)
    {
        try {
            $book = Client::findOrFail($id);
            return response()->json([
                'data' => $book
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Client non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function compteur()
    {
        $validate = Client::count();
        return response()->json([
            'data' => $validate
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $depense = Client::find($id);
        $depense->delete();
        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
    
    public function PutClient(Request $request, $id){
        $user = Client::findOrFail($id);

        if ($user) {
            $user->update([
                'nom_client' => $request->input('nom_client'),
                'telephone' => $request->input('telephone'),
                'montant' => $request->input('montant'),
            ]);
            return response()->json(['message' => 'L\'opération a réussi avec succès'], 200);
        } else {
            return response()->json(['message' => 'Utilisateur n\'existe pas'], 401);
        }
    }
}
