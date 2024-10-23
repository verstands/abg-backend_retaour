<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarchandiseRequest;
use Illuminate\Http\Request;
use App\Models\Marchandises;
use Illuminate\Http\Response;



class MarchandiseController extends Controller
{
    public function index(){
        $book = Marchandises::orderBy('created_at','desc')
            ->with('id_client.id_conteneur')
            ->get();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function store(MarchandiseRequest $request) 
    {
        $validate = Marchandises::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }
    
    public function showClientupdate($id)
    {
        $book = Marchandises::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }
    
    public function UpdateMarchandise(Request $request, $id){
       $marchandise = Marchandises::findOrFail($id);

        if($marchandise){
            $marchandise->update([
                'produit' => $request->input('produit'),
                'qte' => $request->input('qte')
            ]);
            return response()->json(['message' => 'L\'opération a réussi avec succès'], 200);
        }else {
            return response()->json(['message' => 'Utilisateur n\'existe pas'], 401);
        }

    }

    
    public function show(string $id)
    {
        $book = Marchandises::findOrFail($id);
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);
    }

    public function showClient($id)
    {
        $book = Marchandises::where('id_client',$id)->get();
        return response()->json([
            'data' => $book
        ], Response::HTTP_OK);


    }

    public function destroy($id){
        $depense = Marchandises::find($id);
        $depense->delete();

        return response()->json([
            'message' => "La dépense a été supprimée avec succès"
        ], Response::HTTP_OK);
    }

}
