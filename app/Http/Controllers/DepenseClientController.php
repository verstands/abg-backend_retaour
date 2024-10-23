<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepenseClient;
use Illuminate\Http\Response;
use App\Http\Requests\DepenseClientFromRquest;
use App\Models\Depenses;
use App\Models\Compte;
use App\Models\Transaction;

use Carbon\Carbon;


class DepenseClientController extends Controller
{
    public function index()
    {
        $book = DepenseClient::orderBy('created_at', 'desc')
            //->with('type_id')
            //->with('type_id.group')
            //->with('user_id')
            //->with('status_id')
            ->paginate(10);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(DepenseClientFromRquest $request)
    {
        // Récupérer le solde du compte
        $balance = Compte::where('id', 2)->first();

        // Validation de la dépense du client

        // Vérifier si le montant de la dépense est supérieur au solde du compte
        if ($balance && $request->input('montant') > $balance->montant) {
            return response()->json([
                'message' => "Vous ne pouvez pas effectuer une dépense"
            ], Response::HTTP_NOT_FOUND);
        }

        // Calculer le nouveau solde après la dépense
        $validate = DepenseClient::create($request->validated());
        $calcule = $balance->montant - $request->input('montant');  // Soustraction du montant de la dépense
        $balance->update([
            'montant' => $calcule
        ]);

        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }



    public function show(string $id)
    {
        $book = DepenseClient::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }


    public function update(DepenseClientFromRquest $request, string $id)
    {
        $book = DepenseClient::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $depense = DepenseClient::find($id);
        $compte = Compte::where('id', 1)->first();
        if (!$compte) {
            throw new \Exception("Le compte avec l'ID 1 n'a pas été trouvé");
        }
        $calcule = $depense->montant + $compte->montant;
        $compte->update([
            'montant' => $calcule
        ]);
        $depense->delete();

        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }

    public function listeDepnse($id)
    {
        $liste_trsansaction_jour = Depenses::where('ville_id', $id)->orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function listeDepnseId(string $id)
    {
        $book = Depenses::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function listeDepnseIdOne($id)
    {
        $book = DepenseClient::with('id_client.id_pays')
            ->findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function listeDepenseDubai()
    {
        $liste_trsansaction_jour = Depenses::where('ville_id', 1)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    //data depense dubai
    public function listeDepenseDubaiJour()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = DepenseClient::where('id_client', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }

    public function listeDepenseKinshasaJour()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Depenses::where('ville_id', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }



    public function listeDepenseKinshasa()
    {
        $liste_trsansaction_jour = Depenses::where('ville_id', 2)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    //count depot de today
    public function listeDepenseDubaiJourCount()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Transaction::where('pays_provenance', 1)
            ->where('etat', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }


    //count retrait de today
    public function depenseDubaiJourCountSorti()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Transaction::where('pays_provenance', 1)
            ->where('etat', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }
    
     public function depenseDubaiJourCountSortiTs()
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


    //depense kinshasa
    public function listeDepenseDubaiJourCounts()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Depenses::where('ville_id', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }

    //depense kinshasa
    public function EntreKinshasaJourCount()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Transaction::where('pays_provenance', 2)
            ->where('etat', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }
    //count Kinshasa
    public function SortiKinshasaJourCountSorti()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Transaction::where('pays_provenance', 2)
            ->where('etat', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }
    
     public function SortiKinshasaJourCountSortiTs()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = Transaction::where('pays_provenance', 2)
            ->where('etat', 3)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }


    //total transantion kinshasa
    public function KinshasaJourCountTotal()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jourE = Transaction::where('pays_provenance', 2)
            ->where('etat', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $liste_depense_jourS = Transaction::where('pays_provenance', 2)
            ->where('etat', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $c =  ($liste_depense_jourE - $liste_depense_jourS);
        return response()->json([
            'data' => $c
        ], Response::HTTP_OK);
    }


    //depense dubai
    public function listeDepenseKinshasaJourCounts()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = DepenseClient::where('id_client', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }

    public function ListeKinshasaJourCountTotal()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = DepenseClient::where('id_client', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }

    public function balanceDubai()
    {
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $un = Transaction::where('pays_provenance', 1)
            ->where('etat', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $deux = Transaction::where('pays_provenance', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->where('etat', 2)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $c =  $un - $deux;
        return response()->json([
            'data' => $c
        ], Response::HTTP_OK);
    }

    public function totalaJourCount(){
        $total = DepenseClient::where('id_client', 1)
            ->whereDate('created_at', today()) // Pour filtrer par date d'aujourd'hui
            ->sum('montant');
    
        return response()->json([
            'data' => $total
        ], Response::HTTP_OK);
    }
    

    public function balanceKinsha(){
        $deux = Compte::where('id', 2)
            ->first();

        return response()->json([
            'data' => $deux->montant
        ], Response::HTTP_OK);
    }

    public function balanceDubai2()
    {
        $deux = Compte::where('id', 1)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        return response()->json([
            'data' => $deux
        ], Response::HTTP_OK);
    }
}
