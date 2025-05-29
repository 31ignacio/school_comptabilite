<?php

use App\Http\Controllers\AccueilController;
use App\Http\Controllers\AnneScolaireController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\UtilisateurController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'handleLogin'])->name('handleLogin');
    // CLASSE
 Route::middleware(['auth'])->group(function(){

    Route::get('accueil', [AccueilController::class, 'index'])->name('accueil.index');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Utilisateur
    Route::prefix('Utilisateur')->group(function () {
        Route::get('/index', [UtilisateurController::class, 'index'])->name('user.index');
        Route::post('/create', [UtilisateurController::class, 'store'])->name('user.store');
        Route::put('/update/{user}', [UtilisateurController::class, 'update'])->name('user.update');
        Route::delete('delete/{user}', [UtilisateurController::class, 'delete'])->name('user.delete');
        Route::PATCH('/toggleStatus/{user}', [UtilisateurController::class, 'toggleStatus'])->name('user.toggleStatus');
        Route::get('/detail/{user}', [UtilisateurController::class, 'detail'])->name('user.detail');


   });

    Route::prefix('classe')->group(function () {

        Route::get('/index', [ClasseController::class, 'index'])->name('classe.index');
        Route::post('/create', [ClasseController::class, 'store'])->name('classe.store');
        Route::put('/update/{classe}', [ClasseController::class, 'update'])->name('classe.update');
        Route::delete('delete/{classe}', [ClasseController::class, 'delete'])->name('classe.delete');
    });

    // ANNEE SCOLAIRE
    Route::prefix('anneeScolaire')->group(function () {
        Route::get('/index', [AnneScolaireController::class, 'index'])->name('annee.index');
        Route::post('/create', [AnneScolaireController::class, 'store'])->name('annee.store');
        Route::put('/update/{annee}', [AnneScolaireController::class, 'update'])->name('annee.update');
        Route::delete('delete/{annee}', [AnneScolaireController::class, 'delete'])->name('annee.delete');
   });

   // NOUVEAU ELEVE
    Route::prefix('eleve')->group(function () {

        Route::get('/index', [EleveController::class, 'index'])->name('eleve.index');
        Route::post('/create', [EleveController::class, 'store'])->name('eleve.store');
        Route::put('/update/{eleve}', [EleveController::class, 'update'])->name('eleve.update');
        Route::delete('delete/{eleve}', [EleveController::class, 'delete'])->name('eleve.delete');
   
   });


   // ELEVE par classe
    Route::prefix('inscription')->group(function () {
        Route::get('/index', [InscriptionController::class, 'index'])->name('inscription.index');
        Route::get('/show/{inscription}', [InscriptionController::class, 'show'])->name('inscription.show');
        Route::post('/create', [InscriptionController::class, 'store'])->name('inscription.store');
        Route::put('/update/{inscription}', [InscriptionController::class, 'update'])->name('inscription.update');
        Route::delete('delete/{inscription}', [InscriptionController::class, 'delete'])->name('inscription.delete');
   });

   // ELEVE par classe
    Route::prefix('paiement')->group(function () {
        Route::get('/index', [PaiementController::class, 'index'])->name('paiement.index');
        Route::post('/create', [PaiementController::class, 'store'])->name('paiement.store');
        Route::get('/annuler',[PaiementController::class, 'annuler'])->name('paiement.annuler');

   });

 });