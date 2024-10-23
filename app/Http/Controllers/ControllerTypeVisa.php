<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeVisaRequestForm;
use Illuminate\Http\Request;
use App\Models\TypeVisa;
use Illuminate\Http\Response;

class ControllerTypeVisa extends Controller
{
    public function index(){
        $book = TypeVisa::orderBy('created_at','desc')
            ->get();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function liste($id){
        $liste_trsansaction_jour = TypeVisa::all();
        return response()->json([
            'data' => $liste_trsansaction_jour
        ], Response::HTTP_OK);
    }

    public function store(TypeVisaRequestForm $request)
    {
        $validate = TypeVisa::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        $book = TypeVisa::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(TypeVisaRequestForm $request, string $id){
        $book = TypeVisa::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $depense = TypeVisa::find($id);
        $depense->delete();

        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
}
