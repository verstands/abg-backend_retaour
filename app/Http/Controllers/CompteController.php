<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compte;
use Illuminate\Http\Response;
use App\Http\Requests\CompteFromRquest;

class CompteController extends Controller
{
    public function index(){
        $book = Compte::orderBy('created_at','desc')
            //->with('type_id')
            //->with('type_id.group')
            //->with('user_id')
            //->with('status_id')
            ->paginate(10);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(CompteFromRquest $request)
    {
        $validate = Compte::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        $book = Compte::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(CompteFromRquest $request, string $id){
        $book = Compte::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function destroy(string $id){
        $book = Compte::findOrFail($id); 
        $book->delete();
        return response()->json([
            'message' => "L'opération de suppression a réussi avec succès"
        ], Response::HTTP_OK);
    }
}
