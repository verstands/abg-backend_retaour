<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClotureController;
use App\Http\Controllers\ConteneurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepenseClientController;
use App\Http\Controllers\DepenseConteurController;
use App\Http\Controllers\DepenseVisaController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\DettePartenaireController;
use App\Http\Controllers\EntreController;
use App\Http\Controllers\Login;
use App\Http\Controllers\MarchandiseController;
use App\Http\Controllers\PaiementDette;
use App\Http\Controllers\SortiController;
use App\Http\Controllers\VisaController;
use App\Models\Dette;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/SignInUser', [Login::class, 'SignInUser']);
Route::post('/SignUpUser', [Login::class, 'SignUpUser']);

//Route pour controller par l'authentification

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::resource('Entre', 'App\Http\Controllers\EntreController')->except(['create', 'edit']);
    Route::resource('Sorti', 'App\Http\Controllers\SortiController')->except(['create', 'edit']);
    Route::resource('DepenseConteneur', 'App\Http\Controllers\DepenseConteurController')->except(['create', 'edit', 'index']);
    Route::resource('Dette', 'App\Http\Controllers\DetteController')->except(['create', 'edit']);
    Route::resource('Conteneur', 'App\Http\Controllers\ConteneurController')->except(['create', 'edit']);
    Route::resource('Paiement', 'App\Http\Controllers\PaiementController')->except(['create', 'edit']);
    Route::resource('DepenseClient', 'App\Http\Controllers\DepenseClientController')->except(['create', 'edit']);
    Route::resource('Pays', 'App\Http\Controllers\PaysController')->except(['create', 'edit']);
    Route::resource('Role', 'App\Http\Controllers\RoleController')->except(['create', 'edit']);
    Route::resource('Ville', 'App\Http\Controllers\VilleController')->except(['create', 'edit']);
    Route::resource('Compte', 'App\Http\Controllers\CompteController')->except(['create', 'edit']);
    Route::resource('DettePartenaire', 'App\Http\Controllers\DettePartenaireController')->except(['create', 'edit']);
    Route::resource('typedepense', 'App\Http\Controllers\TypeDepenseController')->except(['create', 'edit']);
    Route::resource('cloture', 'App\Http\Controllers\ClotureController')->except(['create', 'edit']);
    Route::resource('marchandise', 'App\Http\Controllers\MarchandiseController')->except(['create', 'edit']);

    Route::get('EntreJourDubai', [EntreController::class, 'index_today_dubai']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntreJourDubaiAll', [EntreController::class, 'index_today_dubaiAll']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntreJourDubaiAllKin', [EntreController::class, 'index_today_dubaiAllKin']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntreJourDubaiAlls/{debut}/{fin}', [EntreController::class, 'index_today_dubaiAlls']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntreJourDubaiAllsKin/{debut}/{fin}', [EntreController::class, 'index_today_dubaiAllsKin']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntreDubai', [EntreController::class, 'index_dubai']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntreDubaiFiltre/{datedebut}/{datefin}', [EntreController::class, 'EntreDubaiFiltre']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntresDubai/{id}', [EntreController::class, 'index']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntresDubaiID/{id}', [EntreController::class, 'EntreId']);
    Route::get('clotureListe', [ClotureController::class, 'listeview']);


    Route::get('EntreJourKinshasa', [EntreController::class, 'index_today_kinshasa']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntreKinshasa/{datedebut}/{datefin}', [EntreController::class, 'index_kinshasa']); //Le route aui affiche les transaction entrées du jour
    Route::get('EntreKinshasa/{id}', [EntreController::class, 'index_kinshasa_id']); //Le route aui affiche les transaction entrées du jour
    Route::get('TransactionSpecialDubai', [EntreController::class, 'TransactionSpecialDubai']); 


    //dette
    Route::get('detteClients/{id}', [DetteController::class, 'liste']);
    //depense
    Route::get('depenses/{id}', [DepenseClientController::class, 'listeDepnse']);
    Route::get('depense/{id}', [DepenseClientController::class, 'listeDepnseId']);
    Route::get('depenseOne/{id}', [DepenseClientController::class, 'listeDepnseIdOne']);
    //depense
    Route::get('depenseDubai', [DepenseClientController::class, 'listeDepenseDubai']);
    Route::get('depenseDubaiJour', [DepenseClientController::class, 'listeDepenseDubaiJour']);
    Route::get('depenseKinshasa', [DepenseClientController::class, 'listeDepenseKinshasa']);
    Route::get('depenseKinshasaJour', [DepenseClientController::class, 'listeDepenseKinshasaJour']);
    //count
    Route::get('depenseDubaiJourCountEntre', [DepenseClientController::class, 'listeDepenseDubaiJourCount']);
    Route::get('depenseDubaiJourCountSorti', [DepenseClientController::class, 'depenseDubaiJourCountSorti']);
    Route::get('depenseDubaiJourCountSortiTs', [DepenseClientController::class, 'depenseDubaiJourCountSortiTs']);
    Route::get('depenseDubaiJourCount', [DepenseClientController::class, 'listeDepenseDubaiJourCounts']);
    Route::get('totalaJourCount', [DepenseClientController::class, 'totalaJourCount']);
    Route::get('balanceDubai', [DepenseClientController::class, 'balanceDubai']);
    Route::get('balanceKinsha', [DepenseClientController::class, 'balanceKinsha']);
    Route::get('balanceDubai2', [DepenseClientController::class, 'balanceDubai2']);
    Route::get('EntreKinshasaJourCountEntre', [DepenseClientController::class, 'EntreKinshasaJourCount']);
    Route::get('SortiKinshasaJourCountSorti', [DepenseClientController::class, 'SortiKinshasaJourCountSorti']);
    Route::get('SortiKinshasaJourCountSortiTs', [DepenseClientController::class, 'SortiKinshasaJourCountSortiTs']);
    Route::get('depenseKinshasaJourCount', [DepenseClientController::class, 'listeDepenseKinshasaJourCounts']);
    Route::get('KinshasaJourCountTotal', [DepenseClientController::class, 'KinshasaJourCountTotal']);
    Route::get('ListeKinshasaJourCountTotal', [DepenseClientController::class, 'ListeKinshasaJourCountTotal']);

   // Route::get('depenseKinshasaJourCount', [DepenseClientController::class, 'listeDepenseKinshasaJourCount']);
    //client conteneur
    Route::get('clientConteneur/{id}', [ClientController::class, 'clientConteneur']);  
    Route::post('client', [ClientController::class, 'store']);  
    Route::get('client/{id}', [ClientController::class, 'show']);  
    Route::delete('client/{id}', [ClientController::class, 'destroy']);  
    Route::get('clientcompteur', [ClientController::class, 'compteur']);  
    Route::get('clientConteneur/{id}', [ClientController::class, 'clientConteneur']);
    Route::post('client', [ClientController::class, 'store']);
    Route::get('clientcompteur', [ClientController::class, 'compteur']);
    Route::put('PutClient/{id}', [ClientController::class, 'PutClient']);
    //users
    Route::get('users', [Login::class, 'users']);
    Route::post('adduser', [Login::class, 'addUsers']);
    Route::delete('Deleteuser/{id}', [Login::class, 'Deleteuser']);


    Route::get('Sortis/{id}', [SortiController::class, 'liste']); // Le route qui affiche toutes les transactions sortis
    Route::get('Sorti_Jour/{id}', [SortiController::class, 'liste_today']); // Le route qui affiche les transactions sortis du jour
    Route::get('SortiKinshasaFiltre/{dateDebut}/{dateFin}', [SortiController::class, 'SortiKinFiltre']); //

    // Les compteurs de l'application
    Route::get('Entre_compteur_entree', [EntreController::class, 'compteur_entree']); // Le compteur qui fait la somme des montants entrants
    Route::get('Entre_compteur_sortie', [EntreController::class, 'compteur_sortie']); // Le compteur qui fait la somme des montants sortants
    Route::get('Balance', [EntreController::class, 'compteur_balance']); // Le compteur qui fait la somme des montants sortants
    //Route::get('index/{id}', [EntreController::class, 'index']);

    //groupage
    Route::get('groupageuser/{id}', [ConteneurController::class, 'groupageuser']); // Le compteur qui fait la somme des montants sortants
    Route::get('totaldepenseconteneur/{id}', [DepenseConteurController::class, 'totaldepenseconteneur']);

    Route::post('verifierEtEnregistrerMontant', [PaiementDette::class, 'verifierEtEnregistrerMontant']);
    Route::get('suppressionMontant/{id}', [PaiementDette::class, 'destroy']);


    Route::get('suppressionMont/{id}', [PaiementDette::class, 'suppression']);
    Route::post('EnregistrementDette/{id}', [PaiementDette::class, 'EnregistrementDette']);

    Route::get('IndexDetteClient/{id}', [DetteController::class, 'IndexDetteClient']);
    Route::delete('DeleteDetteClient/{id}', [DetteController::class, 'DeleteDetteClient']);
    Route::post('storeDetteClient', [DetteController::class, 'storeDetteClient']);

    Route::post('storePaiemntPartenaire', [DettePartenaireController::class, 'storePaiemntPartenaire']);
    Route::get('IndexDetteParteanaire/{id}', [DettePartenaireController::class, 'IndexDetteParteanaire']);
    Route::delete('DeleteDetteParteanaire/{id}', [DettePartenaireController::class, 'DeleteDetteParteanaire']);

    Route::get('/profile', [Login::class, 'profile']);
    Route::put('/profileMod', [Login::class, 'profileMod']);


    Route::get('/showClientMr/{id}', [MarchandiseController::class, 'showClient']);
    Route::get('/showClientUpdate/{id}', [MarchandiseController::class, 'showClientupdate']);
    Route::put('/UpdateMarchandise/{id}', [MarchandiseController::class, 'Updatemarchandise']);
    Route::get('/detteIdTransation/{id}', [DetteController::class, 'detteIdTransation']);
    Route::get('/indexDette/{dateDebut}/{dateFin}', [DetteController::class, 'indexDette']);
    
    Route::resource('visa', 'App\Http\Controllers\VisaController')->except(['create','edit']);
    Route::get('/indexVisa/{dateDebut}/{dateFin}', [VisaController::class, 'indexVisa']);
    Route::get('/suiviVisa/{dateDebut}/{dateFin}', [VisaController::class, 'suiviVisa']);
    Route::get('/depotVisaCount', [VisaController::class, 'depotVisaCount']);
    Route::get('/compte_visa', [VisaController::class, 'compte_visa']);
    Route::get('/CountNonSuiviVisa', [VisaController::class, 'CountNonSuiviVisa']);
    Route::get('/CountSuiviVisa', [VisaController::class, 'CountSuiviVisa']);
    Route::get('/AccepterNon/{id}', [VisaController::class, 'AccepterNon']);

    //depense visa
    Route::resource('depensevisa', 'App\Http\Controllers\DepenseVisaController')->except(['create','edit']);
    Route::get('/countDepenseVisa', [DepenseVisaController::class, 'countDepenseVisa']);

    //type visa
    Route::resource('typevisa', 'App\Http\Controllers\ControllerTypeVisa')->except(['create','edit']);
});
