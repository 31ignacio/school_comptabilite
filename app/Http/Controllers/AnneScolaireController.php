<?php

namespace App\Http\Controllers;

use App\Models\AnneScolaire;
use Exception;
use Illuminate\Http\Request;

class AnneScolaireController extends Controller
{
    //

    /**
     * Afficher la liste des annee scolaire
     */
    public function index(){
        
      
        $annees = AnneScolaire::orderBy('created_at', 'desc')->get();
      
        return view('anneeScolaire.index',compact('annees'));
    }


     /**
     * Enregistrer une année scolaire
    */
    public function store(AnneScolaire $annee,Request $request)
    {
      
        try {

            $exists = AnneScolaire::where('id', '!=', $annee->id)
            ->where('annee', $request->annee)
            ->exists();

            if ($exists) {
                return back()->with('error_message', 'Une année scolaire avec les mêmes informations existe déjà.');
            }

            $annee->annee = $request->annee;
            $annee->save();

            return back()->with('success_message', 'L\'année scolaire a été enregistrée avec succès');
            
        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

     /**
     * Editer une classe
     */
     public function update(AnneScolaire $annee, Request $request){

        $exists = AnneScolaire::where('id', '!=', $annee->id)
            ->where('annee', $request->annee)
            ->exists();

        if ($exists) {
            return back()->with('error_message', 'Une année scolaire avec les mêmes informations existe déjà.');
        }

        try {

            $annee->update([
                'annee' => $request->annee,
            ]);

            return back()->with('success_message', 'L\'année scolaire a été modifiée avec succès.');
        } catch (\Exception $e) {
           
            return back()->with('error_message', 'Une erreur est survenue pendant la modification.');
        }
    }



    /**
     * Supprimer une année scolaire
     */
    public function delete(AnneScolaire $annee)
    {
        try {
            $annee->delete();
            return back()->with('success_message', 'L\'année scolaire a été supprimée avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }


}
