<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetteFromRquest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Http\Response;
use App\Http\Requests\SortiFromRquest;
use App\Models\Dette;
use App\Models\Compte;
use Carbon\Carbon;


class SortiController extends Controller
{
    public function index()
    {
        $book = Transaction::orderBy('created_at', 'desc')
            //->with('type_id')
            //->with('type_id.group')
            //->with('user_id')
            //->with('status_id')
            ->paginate(10);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(SortiFromRquest $request, DetteFromRquest $request2)
    {
        // Vérifier si les champs de la deuxième requête sont vides
        $motifDette = $request2->input('motif_dette');
        $montantDette = $request2->input('montant_dette');
        $idTransaction = $request2->input('id_transaction');
        $pays_provenance = $request->input('pays_provenance');
        $balance = Compte::where('id', $pays_provenance)->first();

        $etat = $request->input('etat');
        if ($etat == 1) {
            // Addition
            if ($balance) {
                $calcule = $balance->montant + $request->input('montant');
                $balance->update([
                    'montant' => $calcule
                ]);
            }
        } elseif ($etat == 2) {
            if ($balance && $request->input('montant') > $balance->montant) {
                return response()->json([
                    'message' => "Vous ne pouvez pas effectuer une transaction"
                ], Response::HTTP_OK);
            }

            if ($balance) {
                $calcule = $balance->montant - $request->input('montant');
                $balance->update([
                    'montant' => $calcule
                ]);
            }
        } else {
            return response()->json([
                'message' => "L'état n'est pas valide"
            ], Response::HTTP_BAD_REQUEST);
        }
        $validate = Transaction::create($request->validated());

        if (empty($motifDette) && empty($montantDette) && empty($idTransaction)) {
            // Si tous les champs sont vides, enregistrer uniquement les données de la première requête
            return response()->json([
                'message' => "L'opération a réussi avec succès"
            ], Response::HTTP_OK);
        } else {
            // Si au moins un champ n'est pas vide, enregistrer à la fois les données de la première et de la deuxième requête
            $dete = Dette::create($request2->validated());

            return response()->json([
                'message' => "L'opération a réussi avec succès"
            ], Response::HTTP_OK);
        }
    }


    public function show(string $id)
    {
        $book = Transaction::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function SortiKinFiltre($dateDebut, $dateFin){
        $liste_transaction = Transaction::where('pays_provenance', 2)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $liste_transaction
        ], Response::HTTP_OK);
    }

    public function update(SortiFromRquest $request, string $id)
    {
        $book = Transaction::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function liste($id)
    {
        $liste_transaction = Transaction::where('etat', '=', '1')->where('pays_provenance', $id)->orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'data' => $liste_transaction
        ], Response::HTTP_OK);
    }

    // La liste de transaction entrée du jour
    public function liste_today()
    {
        $liste_transaction_jour = Transaction::whereDate('created_at', Carbon::today())
            ->where('pays_provenance', 2)
            ->where('etat', '1')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'data' => $liste_transaction_jour
        ], Response::HTTP_OK);
    }
    public function destroy(string $id)
    {
        $depense = Transaction::find($id);
        if (!$depense) {
            return response()->json([
                'message' => "La dépense avec l'ID $id n'a pas été trouvée"
            ], Response::HTTP_NOT_FOUND);
        }

        $compte = Compte::find(2);
        if (!$compte) {
            return response()->json([
                'message' => "Le compte avec l'ID 2 n'a pas été trouvé"
            ], Response::HTTP_NOT_FOUND);
        }

        if ($depense->etat == 1) {
            $calcule = $compte->montant - $depense->montant;
        } elseif ($depense->etat == 2) {
            $calcule = $compte->montant + $depense->montant;
        }

        $compte->update([
            'montant' => $calcule
        ]);

        $depense->delete();

        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
}
