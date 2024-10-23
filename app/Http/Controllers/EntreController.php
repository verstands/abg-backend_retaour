<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetteFromRquest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Http\Response;
use App\Http\Requests\EntreFromRquest;
use App\Models\Compte;
use App\Models\DepenseClient;
use App\Models\Dette;
use App\Models\DettePartenaire;
use Carbon\Carbon;

class EntreController extends Controller
{
    // La liste de toutes les transactions entrées
    public function index($id)
    {
        $liste_transaction = Transaction::where('etat', '=', '0')->where('pays_provenance', $id)->orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'data' => $liste_transaction
        ], Response::HTTP_OK);
    }

    // La liste de transaction entrée du jour
    public function index_today_dubai()
    {
        $liste_trsansaction_jour = Transaction::wheredate('created_at', '=', Carbon::today())->where('pays_provenance', 1)->orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function index_today_dubaiAll()
    {
        $liste_trsansaction_jour = Transaction::wheredate('created_at', '=', Carbon::today())
            ->where('pays_provenance', 1)
            ->orderBy('created_at', 'desc')
            ->with([
                'pays_provenance.id_pays',
                'pays_destinateut.id_pays'
            ])
            ->get();
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function index_today_dubaiAllKin()
    {
        $liste_trsansaction_jour = Transaction::wheredate('created_at', '=', Carbon::today())
            ->where('pays_provenance', 2)
            ->orderBy('created_at', 'desc')
            ->with([
                'pays_provenance.id_pays',
                'pays_destinateut.id_pays'
            ])
            ->get();
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function index_today_dubaiAlls($debut, $fin){
        $liste_trsansaction_jour = Transaction::where('pays_provenance', 1)
            ->whereBetween('created_at', [$debut, $fin])
            ->orderBy('created_at', 'desc')
            ->with([
                'pays_provenance.id_pays',
                'pays_destinateut.id_pays'
            ])
            ->get();
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function index_today_dubaiAllsKin($debut, $fin)
    {
        $liste_trsansaction_jour = Transaction::where('pays_provenance', 2)
            ->whereBetween('created_at', [$debut, $fin])
            ->orderBy('created_at', 'desc')
            ->with([
                'pays_provenance.id_pays',
                'pays_destinateut.id_pays'
            ])
            ->get();
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function index_dubai()
    {   
        $liste_trsansaction_jour = Transaction::where('pays_provenance', 1)->orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function EntreDubaiFiltre($datedebut, $datefin)
    {
        $liste_trsansaction_jour = Transaction::where('pays_provenance', 1)
            ->whereBetween('created_at', [$datedebut, $datefin])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function index_kinshasa($datedebut, $datefin){
        $liste_trsansaction_jour = Transaction::where('pays_provenance', 2)
            ->whereBetween('created_at', [$datedebut, $datefin])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function index_today_kinshasa()
    {
        $liste_trsansaction_jour = Transaction::wheredate('created_at', '=', Carbon::today())->where('pays_provenance', 2)->orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }


    public function compteur_entree()
    {
        $compteur_entree = Transaction::sum('montant');
        return response()->json([
            'data' => $compteur_entree
        ], Response::HTTP_OK);
    }

    //La somme de toutes les sorties
    public function compteur_sortie()
    {
        $compteur_entree = Transaction::where('etat', '=', '1')->sum('montant');
        return response()->json([
            'data' => $compteur_entree
        ], Response::HTTP_OK);
    }

    //La somme des entrées moins les sorties (La balance)
    public function compteur_balance()
    {
        $compteur_entree = Transaction::where('etat', '=', '0')->sum('montant');
        $compteur_entree = Transaction::where('etat', '=', '1')->sum('montant');
        $balance = $compteur_entree - $compteur_entree;
        return response()->json([
            'data' => $balance
        ], Response::HTTP_OK);
    }

    //

    //Creation des transactions
    public function store(EntreFromRquest $request, DetteFromRquest $request2)
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
        } elseif ($etat == 3) {
            $calcule = $balance->montant - $request->input('montant');
            $balance->update([
                'montant' => $calcule
            ]);
        } else {
            return response()->json([
                'message' => "L'état n'est pas valide"
            ], Response::HTTP_BAD_REQUEST);
        }
        $validate = Transaction::create($request->validated());
        $idTransaction = $validate->id;


        if (empty($motifDette) && empty($montantDette)) {
            // Si tous les champs sont vides, enregistrer uniquement les données de la première requête
            return response()->json([
                'message' => "L'opération a réussi avec succès"
            ], Response::HTTP_OK);
        } else {
            // Si au moins un champ n'est pas vide, enregistrer à la fois les données de la première et de la deuxième requête

            $dete = Dette::create(array_merge($request2->validated(), ['id_transaction' => $idTransaction]));
            return response()->json([
                'message' => "L'opération a réussi avec succès"
            ], Response::HTTP_OK);
        }
    }



    public function EntreId($id)
    {
        $liste_transaction = Transaction::with([
            'pays_provenance.id_pays',
            'pays_destinateut.id_pays'
        ])->findOrFail($id);

        return response()->json([
            'data' => $liste_transaction
        ], Response::HTTP_OK);
    }



    //Recuperation d'une transaction avec son identifiant
    public function show(string $id)
    {
        $liste_transaction = Transaction::findOrFail($id);
        return response()->json([
            'data' => $liste_transaction
        ], Response::HTTP_OK);
    }

    //Modification d'une transaction
    public function update(EntreFromRquest $request, string $id)
    {
        $liste_transaction = Transaction::findOrFail($id);
        $liste_transaction->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
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

        $compte = Compte::find(1);
        if (!$compte) {
            return response()->json([
                'message' => "Le compte avec l'ID 1 n'a pas été trouvé"
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
    
     public function TransactionSpecialDubai()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Transaction::where('pays_provenance', 1)
            ->where('etat', 3)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }
}
