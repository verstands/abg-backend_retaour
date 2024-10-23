<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DettePartenaire;
use Illuminate\Http\Response;
use App\Http\Requests\DetteParteneurFromRquest;
use App\Http\Requests\PaiementFromRquest;
use App\Http\Requests\PaiementpaternaireFromRequest;
use App\Models\Compte;
use App\Models\Paiement;
use App\Models\Paiementpaternaires;
use App\Models\Transaction;

class DettePartenaireController extends Controller
{
    public function index(){
        $book = DettePartenaire::orderBy('created_at','desc')
            ->with('transaction_id')
            //->with('type_id.group')
            //->with('user_id')
            //->with('status_id')
            ->get();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(DetteParteneurFromRquest $request)
    {
        $validate = DettePartenaire::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function storePaiemntPartenaire(PaiementpaternaireFromRequest $request)
    {
        $validate = Paiementpaternaires::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        $book = DettePartenaire::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(DetteParteneurFromRquest $request, string $id){
        $book = DettePartenaire::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $dettePartenaire = DettePartenaire::find($id);
        if (!$dettePartenaire) {
            return response()->json([
                'message' => "dettePartenaire introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
        $trasaction = Transaction::find($dettePartenaire->transaction_id);
        
        if (!$trasaction) {
            return response()->json([
                'message' => "Transaction introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
        $compte = Compte::find($trasaction->pays_provenance);
        if (!$compte) {
            return response()->json([
                'message' => "Compte introuvable"
            ], Response::HTTP_NOT_FOUND);
        }

        $nouveauMontant = $compte->montant - $dettePartenaire->montant;
    
        // Vérifiez si le nouveau montant est négatif, si c'est le cas, fixez-le à zéro
        $nouveauMontant = max(0, $nouveauMontant);
    
        $compte->update([
            "montant" => $nouveauMontant
        ]);

        $dettePartenaire->delete();
        return response()->json([
            'message' => "La dépense a été supprimée avec succès",
        ], Response::HTTP_OK);
    }

    public function DeleteDettePartenanire($id){
        $dettePartenaire = DettePartenaire::where('id', $id)->first();
        
        if (!$dettePartenaire) {
            return response()->json([
                'message' => "dettePartenaire introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $trasaction = Transaction::find($dettePartenaire->transaction_id);
    
        if (!$trasaction) {
            return response()->json([
                'message' => "Transaction introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $compte = Compte::find($trasaction->pays_provenance);
    
        if (!$compte) {
            return response()->json([
                'message' => "Compte introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $nouveauMontant = $compte->montant - $dettePartenaire->montant_paye;
    
        // Vérifiez si le nouveau montant est négatif, si c'est le cas, fixez-le à zéro
        $nouveauMontant = max(0, $nouveauMontant);
    
        $compte->update([
            "montant" => $nouveauMontant
        ]);
    
        $dettePartenaire->delete();
    
        return response()->json([
            'message' => "Le paiement a été supprimé avec succès"
        ], Response::HTTP_OK);
    }


    public function IndexDetteParteanaire($id){
        $paiements = Paiementpaternaires::where('id_partenaire', $id)->get();
        return response()->json([
            'data' => $paiements
        ], Response::HTTP_OK);
    }


    public function DeleteDetteParteanaire($id){
        $paiements = Paiementpaternaires::where('id', $id)->first();
        
        if (!$paiements) {
            return response()->json([
                'message' => "Paiement introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $trasaction = DettePartenaire::find($paiements->id_partenaire);
    
        if (!$trasaction) {
            return response()->json([
                'message' => "dette client introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $compte = Compte::find($trasaction->transaction_id);
    
        if (!$compte) {
            return response()->json([
                'message' => "Compte introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $nouveauMontant = $compte->montant - $paiements->montant;
    
        // Vérifiez si le nouveau montant est négatif, si c'est le cas, fixez-le à zéro
        $nouveauMontant = max(0, $nouveauMontant);
    
        $compte->update([
            "montant" => $nouveauMontant
        ]);
    
        $paiements->delete();
    
        return response()->json([
            'message' => "Le paiement a été supprimé avec succès"
        ], Response::HTTP_OK);
    }
}
