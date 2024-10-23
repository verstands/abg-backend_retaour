<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepenseConteneur;
use Illuminate\Http\Response;
use App\Http\Requests\DepenseConteneurFromRquest;

class DepenseConteurController extends Controller
{
    public function index(){
        $book = DepenseConteneur::orderBy('created_at','desc')
            //->with('type_id')
            //->with('type_id.group')
            //->with('user_id')
            //->with('status_id')
            ->paginate(10);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(DepenseConteneurFromRquest $request)
    {
        $validate = DepenseConteneur::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id){
        $depenseConteneur = DepenseConteneur::with('id_conteneur', 'id_typedepense')->where('id_conteneur', $id)->get();
        if (!$depenseConteneur) {
            return response()->json([
                'message' => 'vide'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'data' => $depenseConteneur
        ], Response::HTTP_OK);
    }

    public function totaldepenseconteneur(string $id){
        $depenseConteneur = DepenseConteneur::where('id_conteneur', $id)->sum('montant');
        return response()->json([
            'data' => $depenseConteneur
        ], Response::HTTP_OK);
    }
    

    
    public function update(DepenseConteneurFromRquest $request, string $id){
        $book = DepenseConteneur::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function destroy(string $id){
        $depense = DepenseConteneur::find($id);
        $depense->delete();
        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }
}
