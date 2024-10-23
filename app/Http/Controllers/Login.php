<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFromRquest;
use App\Http\Requests\RegisterFromRquest;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Http\Response;

class Login extends Controller
{
   
    public function signInUser(LoginFromRquest $request)
    {
        $validated = $request->validated();
        
        if (Auth::attempt($validated)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            unset($user->password);
            return response()->json([
                'data' => $user,
                'token' => $token
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Email password incorrect'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function SignUpUser(RegisterFromRquest $request){
        $validate = User::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }   


    public function users(){
        $users = User::all();
        return response()->json([
            'data' => $users
        ], Response::HTTP_OK);
    }

    public function addUsers(UserRequest $request){
        $validate = User::create($request->validated());
        return response()->json([
            'message' => "L'opération a réussi avec succès"
        ], Response::HTTP_OK);
    }

    public function Deleteuser(string $id)
    {
        $depense = User::find($id);
        $depense->delete();

        return response()->json([
            'message' => "La l'utilisateur a été supprimée avec succès"
        ], Response::HTTP_OK);
    }

    public function profile(){
        $profile = Auth::user();
        return response()->json([
            'data' => $profile
        ], Response::HTTP_OK);
    }

    public function profileMod(Request $request){
        $user = Auth::user();
        if ($user) {
            $user->update([
                'nom' => $request->input('nom'),
                'postnom' => $request->input('postnom'),
                'email' => $request->input('email'),
            ]);
            return response()->json(['message' => 'Profil mis à jour avec succès'], 200);
        } else {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }
    }
}
