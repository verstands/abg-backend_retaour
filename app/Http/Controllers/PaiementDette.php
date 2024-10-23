<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaiementFromRquest;
use App\Models\Client;
use App\Models\Compte;
use App\Models\Dette;
use App\Models\PaiementsDette;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PaiementDette extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $liste_paiement_dette = PaiementsDette::orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => $liste_paiement_dette
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PaiementFromRquest $request, $clientId, $valeurSaisie)
    {

        $client_id = $request->input('client_id');
        $montant_payer = $request->input('montant_payer');

        if ($montant_payer = Client::where('montant', '>=', $montant_payer)) {
            $validated = PaiementsDette::create($request->validated());
            return response()->json([
                'message' => "L'opération a réussi avec succès"
            ], Response::HTTP_OK);
        }else {
            return response()->json([
                'message' => "L'opération n\'a pas reussi "
            ], Response::HTTP_OK);
        }

    }

    public function EnregistrementDette(Request $request, string $id){
        $recuperationTrasanction = Transaction::where('id', $id)->first();
        $pays_provenan = $recuperationTrasanction->pays_provenance;
        $transaction_id = $request->input('transaction_id');
        $montant_payer = $request->input('montant_payer');

        $comptes = Compte::where('id', $pays_provenan)->first();

        $calculous = $comptes->montant + $montant_payer;

        $paiement = new PaiementsDette();
        $paiement->transaction_id = $transaction_id;
        $paiement->montant_payer =$montant_payer;
        $paiement->save();
        $comptes->update([
            'montant' => $calculous
        ]);
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function verifierEtEnregistrerMontant(Request $request)
    {
        $client_id = $request->input('client_id');
        $montant_payer = $request->input('montant_payer');
        $pays_provenance = $request->input('pays_provenance');
        $balance = Compte::where('id', $pays_provenance)->first();

        $client = Client::find($client_id);
        //$etat = $request->input('etat');

        if ($pays_provenance == 1 ) {
            if ($client && $client->montant >= $montant_payer ) {
                $paiement = new PaiementsDette();
                $paiement->client_id = $client_id;
                $paiement->montant_payer = $montant_payer;
                $paiement->save();
                $Calcule = $balance->montant + $request->input('montant_payer');
                $balance->update([
                    'montant' => $Calcule
                ]);
                return response()->json([
                    'message' => "L'opération a réussi avec succès"
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => "L'opération n'a pas réussi"
                ], Response::HTTP_OK);
            }
        }if ($pays_provenance == 2) {
            if ($client && $client->montant >= $montant_payer ) {
                $paiement = new PaiementsDette();
                $paiement->client_id = $client_id;
                $paiement->montant_payer = $montant_payer;
                $paiement->save();
                $Calcule = $balance->montant + $request->input('montant_payer');
                $balance->update([
                    'montant' => $Calcule
                ]);

                return response()->json([
                    'message' => "L'opération a réussi avec succès"
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => "L'opération n'a pas réussi"
                ], Response::HTTP_OK);
            }
        }else {
            return response()->json([
                'message' => "L'opération n'a pas aboutit"
            ], Response::HTTP_OK);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaiementFromRquest $request, string $id)
    {
        $liste_paiement_dette = PaiementsDette::findOrFail($id);
        $liste_paiement_dette->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function suppression(Request $request, string $id){
        $solde_compte = PaiementsDette::find($id);
        $recuperationTrasanction = Transaction::where('id', $solde_compte->transaction_id)->first();
        $pays_provenance = $recuperationTrasanction->pays_provenance;
        $comptes = Compte::where('id', $pays_provenance);

        if (!$solde_compte) {
            return response()->json([
                'message' => "La dépense avec l'ID $id n'a pas été trouvée"
            ], Response::HTTP_NOT_FOUND);
        }
        if ($comptes) {
            $compte = Compte::find(1);
            if (!$compte) {
                return response()->json([
                'message' => "Le compte avec l'ID 1 n'a pas été trouvé"
            ], Response::HTTP_NOT_FOUND);
            }
           // if ($pays_provenance) {
                $calcule = $compte->montant - $solde_compte->montant_payer;
           // }
            $compte->update([
                'montant' => $calcule
            ]);

            $solde_compte->delete();

            return response()->json([
                'message' => "La dépense a été supprimée avec succès"
            ], Response::HTTP_OK);

        }if ($comptes) {
            $compte = Compte::find(1);
            if (!$compte) {
                return response()->json([
                'message' => "Le compte avec l'ID 1 n'a pas été trouvé"
            ], Response::HTTP_NOT_FOUND);
            }
           // if ($pays_provenance) {
                $calcule = $compte->montant - $solde_compte->montant_payer;
           // }
            $compte->update([
                'montant' => $calcule
            ]);

            $solde_compte->delete();

            return response()->json([
                'message' => "La dépense a été supprimée avec succès"
            ], Response::HTTP_OK);

        }
        else {
            return response()->json([
                'message' => "La suppression n\'a pas aboutit "
            ], Response::HTTP_OK);
        }

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $liste_paiement_dette = PaiementsDette::findOrFail($id);
        $pays_provenance = $request->input('pays_provenance');
        $balance = Compte::where('id', $pays_provenance)->first();
        $recuperation_montant = PaiementsDette::where('montant_payer', $id)->sum('montant_payer');
        if ($pays_provenance == 1) {
            if ($balance) {
                $calculos =  $balance->montant - $recuperation_montant;
                //dd($calculos);
                $balance->update([
                    'montant' => $calculos
                ]);
                $liste_paiement_dette->delete();
            }


            return response()->json([
            'message' => "L'opération de suppression a réussi avec succès"
            ], Response::HTTP_OK);
        }if ($pays_provenance == 2) {

        }else {
            return response()->json([
                'message' => "L'opération n'a pas réussi"
            ], Response::HTTP_OK);
        }

    }
}
