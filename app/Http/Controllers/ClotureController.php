<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClotureRequest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Http\Response;
use App\Models\Compte;
use App\Models\DepenseClient;
use App\Models\Dette;
use App\Models\DettePartenaire;
use App\Models\Cloture;
use App\Models\DepenseVisa;
use App\Models\Visa;
use Carbon\Carbon;

class ClotureController extends Controller
{
     public function index(){
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $entredubai = Transaction::where('pays_provenance', 1)
            ->where('etat', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $transactionspecial = Transaction::where('pays_provenance', 1)
            ->where('etat', 3)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $sortidubai = Transaction::where('pays_provenance', 1)
            ->where('etat', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $entreKinhsasa = Transaction::where('pays_provenance', 2)
            ->where('etat', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        $sortiKinhsasa = Transaction::where('pays_provenance', 2)
            ->where('etat', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        $depenseDubai = DepenseClient::where('id_client', 1)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        $depenseKinshasa = DepenseClient::where('id_client', 2)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $dettepartenaire = DettePartenaire::where('etat', 0)
             ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        $detteclient = Dette::where('etat_dette', 0)
            ->whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant_dette');

        $balanceDubai = $entredubai - $sortidubai - $depenseDubai;

        $balanceKinshasa = $entreKinhsasa - $sortiKinhsasa - $depenseKinshasa;

        $depotVisas = Visa::join('type_visas', 'visas.id_typevisa', '=', 'type_visas.id')
            ->whereDate('visas.created_at', $aujourd_hui)
            ->where('visas.etat', 1)
            ->sum('type_visas.montant');

        $sortiVisas = Visa::join('type_visas', 'visas.id_typevisa', '=', 'type_visas.id')
            ->whereDate('visas.created_at', $aujourd_hui)
            ->where('visas.etat', 2)
            ->sum('type_visas.montant');
        
        $depenseVisa = DepenseVisa::whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');

        

        return response()->json([
            'entredubai' =>  $entredubai,
            'sortidubai' =>  $sortidubai,
            'entreKinhsasa' =>  $entreKinhsasa,
            'sortiKinhsasa' =>  $sortiKinhsasa,
            'depenseDubai' =>  $depenseDubai,
            'depenseKinshasa' =>  $depenseKinshasa,
            'dettepartenaire' =>  $dettepartenaire,
            'detteclient' =>  $detteclient,
            'balanceDubai' =>  $balanceDubai,
            'balanceKinshasa' =>  $balanceKinshasa,
            'transactionspecial' => $transactionspecial,
            'depenseVisa' => $depenseVisa,
            'entreVisa' => $depotVisas,
            'sortiVisa' => $sortiVisas
        ], Response::HTTP_OK);
    }

    public function listeview(){
        $data = Cloture::all();
        return response()->json([
            'data' => $data
        ], Response::HTTP_OK);
    }

    public function store(ClotureRequest $request)
    {
        $validate = Cloture::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function show(string $id)
    {
        $book = Cloture::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(ClotureRequest $request, string $id){
        $book = Cloture::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $depense = Cloture::find($id);
        $depense->delete();

        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
}
