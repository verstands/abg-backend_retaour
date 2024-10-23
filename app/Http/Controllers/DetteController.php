<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dette;
use App\Models\PaiementsDette;
use Illuminate\Http\Response;
use App\Http\Requests\DetteFromRquest;
use App\Http\Requests\PaiementDetteFromRequest;
use App\Models\Compte;
use App\Models\Transaction;

class DetteController extends Controller
{
    public function index(){
        $book = Dette::orderBy('created_at','desc')
            ->with('id_transaction')
            ->get();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    //paieemnt dette client
    public function IndexDetteClient($id){
        $paiements = PaiementsDette::where('transaction_id', $id)->get();
        return response()->json([
            'data' => $paiements
        ], Response::HTTP_OK);
    }

    public function storeDetteClient(PaiementDetteFromRequest $request){
    $transaction = Transaction::find($request->transaction_id);
    $dette = Dette::where('id_transaction', $transaction->id)->first();

    //$detteCheck = Dette::find($request->transaction_id);

    if (!$transaction) {
        return response()->json([
            'message' => "Transaction introuvable"
        ], Response::HTTP_NOT_FOUND);
    }

    $compte = Compte::find($transaction->pays_provenance);

    if (!$compte) {
        return response()->json([
            'message' => "Compte introuvable"
        ], Response::HTTP_NOT_FOUND);
    }

    $nouveauMontant = $compte->montant + $request->montant_paye;
    $cal =  $dette->montant_paye + $request->montant_paye;

    if($cal >  $dette->montant_dette){
        return response()->json([
            'message' => "Le montant que vous avez saisi est au super a la dette que vous avez".$cal
        ], Response::HTTP_BAD_REQUEST);
    }

    $paiementDette = PaiementsDette::create($request->validated());


    // Assurez-vous que le nouveau montant ne devienne pas négatif
    $nouveauMontant = max(0, $nouveauMontant);

    $compte->update([
        "montant" => $nouveauMontant
    ]);

    // Mise à jour du montant de la dette

    if ($dette) {
        $nouveauMontantDette = $dette->montantpayer + $request->montant_paye;
        $nouveauMontantDette = max(0, $nouveauMontantDette);

        $dette->update([
            "montantpayer" => $nouveauMontantDette
        ]);
    }

    return response()->json([
        'message' => "L'opération a réussi avec succès"
    ], Response::HTTP_OK);
}

    

    public function DeleteDetteClient($id){
        $paiements = PaiementsDette::where('id', $id)->first();
        
        if (!$paiements) {
            return response()->json([
                'message' => "Paiement introuvable"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $trasaction = Transaction::find($paiements->transaction_id);
    
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
    
        $nouveauMontant = $compte->montant - $paiements->montant_paye;
    
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
    

    public function liste($id){
        $liste_trsansaction_jour = Dette::where('pays_provenance', $id)->where('etat', '=', '0')->orderBy('created_at','desc')->paginate(10);
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function store(DetteFromRquest $request)
    {
        $validate = Dette::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        $book = Dette::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function detteIdTransation(string $id)
    {
        $book = Dette::where('id_transaction', $id)->first();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(DetteFromRquest $request, string $id){
        $book = Dette::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $depense = Dette::find($id);
        $depense->delete();
        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
}
