<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use Illuminate\Http\Response;
use App\Http\Requests\VilleFromRquest;

class VilleController extends Controller
{
    public function index(){
        $book = Ville::orderBy('created_at','desc')
            //->with('type_id')
            //->with('type_id.group')
            //->with('user_id')
            //->with('status_id')
            ->paginate(10);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(VilleFromRquest $request)
    {
        $validate = Ville::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    
    public function show(string $id)
    {
        $book = Ville::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    
    public function update(VilleFromRquest $request, string $id){
        $book = Ville::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'message' => "L'opération de mise à jour a réussi avec succès"
        ], Response::HTTP_OK);
    }
}
