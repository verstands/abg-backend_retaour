<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepenseVisaRequestForm;
use Illuminate\Http\Request;
use App\Models\DepenseVisa;
use Illuminate\Http\Response;
use Carbon\Carbon;

class DepenseVisaController extends Controller
{
    public function index(){
        $book = DepenseVisa::orderBy('created_at','desc')
            ->get();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function countDepenseVisa(){
        $aujourd_hui = Carbon::now()->format('Y-m-d');
        $liste_depense_jour = DepenseVisa::whereDate('created_at', $aujourd_hui)
            ->orderBy('created_at', 'desc')
            ->sum('montant');
        return response()->json([
            'data' => $liste_depense_jour
        ], Response::HTTP_OK);
    }

    public function store(DepenseVisaRequestForm $request)
    {
        $validate = DepenseVisa::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        $book = DepenseVisa::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(DepenseVisaRequestForm $request, string $id){
        $book = DepenseVisa::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id){
        $depense = DepenseVisa::find($id);
        $depense->delete();
        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
}
