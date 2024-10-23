<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeDepense;
use Illuminate\Http\Response;
use App\Http\Requests\TypeDepenseRequest;

class TypeDepenseController extends Controller
{
    public function index(){
        $book = TypeDepense::orderBy('created_at','desc')
            ->get();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function liste($id){
        $liste_trsansaction_jour = TypeDepense::all();
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function store(TypeDepenseRequest $request)
    {
        $validate = TypeDepense::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        $book = TypeDepense::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(TypeDepenseRequest $request, string $id){
        $book = TypeDepense::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $depense = TypeDepense::find($id);
        $depense->delete();

        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
}
