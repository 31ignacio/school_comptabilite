<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Exception;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    //

     /**
     * Afficher la liste des classes
     */
    public function index(){
        
        $classes = Classe::orderBy('created_at', 'desc')->get();
      
        return view('classe.index',compact('classes'));
    }

     /**
     * Enregistrer une classe
    */
    public function store(Classe $classe,Request $request)
    {
      
        try {
            $exists = Classe::where('id', '!=', $classe->id)
            ->where('nom', $request->classe)
            ->where('scolarite', $request->scolarite)
            ->exists();

            if ($exists) {
                return back()->with('error_message', 'Une classe avec les mêmes informations existe déjà.');
            }

            $classe->nom = $request->classe;
            $classe->scolarite = $request->scolarite;
            $classe->save();

            return back()->with('success_message', 'La classe a été ajoutée avec succès');
            
        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }


    /**
     * Editer une classe
     */
     public function update(Classe $classe, Request $request){

        $exists = Classe::where('id', '!=', $classe->id)
            ->where('nom', $request->classe)
            ->where('scolarite', $request->scolarite)
            ->exists();

        if ($exists) {
            return back()->with('error_message', 'Une classe avec les mêmes informations existe déjà.');
        }

        try {

            $classe->update([
                'nom' => $request->classe,
                'scolarite' => $request->scolarite,
            ]);

            return back()->with('success_message', 'La classe a été modifiée avec succès.');
        } catch (\Exception $e) {
           
            return back()->with('error_message', 'Une erreur est survenue pendant la modification.');
        }
    }



    /**
     * Supprimer une classe
     */
    public function delete(Classe $classe)
    {
        try {
            $classe->delete();
            return back()->with('success_message', 'La classe a été supprimée avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }
}
