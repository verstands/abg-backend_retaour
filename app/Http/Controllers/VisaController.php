<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visa;
use Illuminate\Http\Response;
use App\Http\Requests\VisaRequestForm;
use App\Models\Compte;
use App\Models\TypeVisa;
use Carbon\Carbon;

class VisaController extends Controller
{
    public function indexVisa($datedebut, $datefin)
    {
        // Ajouter 1 jour à la date de fin pour inclure les enregistrements du jour de datefin
        $datefin = date('Y-m-d', strtotime($datefin . ' +1 day'));
        $liste_trsansaction_jour = Visa::whereBetween('created_at', [$datedebut, $datefin])
            ->with('id_typevisa')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }


    public function depotVisaCount(){
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Visa::join('type_visas', 'visas.id_typevisa', '=', 'type_visas.id')
            ->whereDate('visas.created_at', $aujourd_hui)
            ->sum('type_visas.montant');

        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }

    public function compte_visa(){
        $compteur_entree = Compte::where('id', 3)->first();

        if ($compteur_entree) {
            return response()->json([
                'data' => $compteur_entree->montant
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => 'Visa not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function suiviVisa($datedebut, $datefin)
    {
        $liste_trsansaction_jour = Visa::where('etat', 1)
            ->whereBetween('created_at', [$datedebut, $datefin])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }
    public function store(VisaRequestForm $request){
        $compte = Compte::find(3);
    
        if (!$compte) {
            return response()->json([
                'message' => "Compte non trouvé"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $type = TypeVisa::find($request->input('id_typevisa'));
    
        if (!$type) {
            return response()->json([
                'message' => "Type de visa non trouvé"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $montantActuel = $compte->montant;
        $nouveauMontant = $montantActuel + $type->montant;
    
        $compte->update([
            'montant' => $nouveauMontant
        ]);
    
        Visa::create($request->validated());
    
        return response()->json([
            'message' => 'L\'opération a réussi avec succès',
        ], Response::HTTP_OK);
    }



    public function show(string $id)
    {
        $book = Visa::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }


    public function update(VisaRequestForm $request, string $id)
    {
        $book = Visa::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id){
        $depense = Visa::find($id);
    
        if (!$depense) {
            return response()->json([
                'message' => "Dépense non trouvée"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $typeVisa = TypeVisa::find($depense->id_typevisa);
    
        if (!$typeVisa) {
            return response()->json([
                'message' => "Type de visa non trouvé"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $compte = Compte::find(3);
    
        if (!$compte) {
            return response()->json([
                'message' => "Compte non trouvé"
            ], Response::HTTP_NOT_FOUND);
        }
    
        $nouveauMontant = $compte->montant - $typeVisa->montant;
    
        $compte->update([
            'montant' => $nouveauMontant
        ]);
    
        $depense->delete();
    
        return response()->json([
            'message' => "L'enregistrement a été supprimée avec succès",
            'nouveau_montant' => $nouveauMontant
        ], Response::HTTP_OK);
    }

    public function CountNonSuiviVisa(){
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Visa::join('type_visas', 'visas.id_typevisa', '=', 'type_visas.id')
            ->whereDate('visas.created_at', $aujourd_hui)
            ->where('visas.etat', 1 )
            ->sum('type_visas.montant');

        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }

    public function CountSuiviVisa(){
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Visa::join('type_visas', 'visas.id_typevisa', '=', 'type_visas.id')
            ->whereDate('visas.created_at', $aujourd_hui)
            ->where('visas.etat', 0)
            ->sum('type_visas.montant');

        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }

    public function AccepterNon($id){
        $depense = Visa::find($id);
        if (!$depense) {
            return response()->json([
                'message' => "non trouvée"
            ], Response::HTTP_NOT_FOUND);
        }
        
        if($depense->etat == 1){
            $depense->update([
                'etat' => 0
            ]);
        }else if($depense->etat == 0){
            $depense->update([
                'etat' => 1
            ]);
        }
    }
}
