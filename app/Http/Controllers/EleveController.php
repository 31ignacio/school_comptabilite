<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EleveController extends Controller
{
    //

    /**
     * Afficher la liste des eleves
    */
    public function index(){
        
        $eleves = Eleve::orderBy('created_at', 'desc')->get();
      
        return view('eleve.index',compact('eleves'));
    }

    
    /**
     * Enregistrer un nouvel élève
    */
    public function store(Eleve $eleve,Request $request)
    {
      
        try 
        {
            $exists = Eleve::where('id', '!=', $eleve->id)
            ->where('nom', $request->nom)
            ->where('prenom', $request->prenom)
            ->exists();

            if ($exists) {
                return back()->with('error_message', 'Un élève avec les mêmes informations existe déjà.');
            }

            $dernierEleve = DB::table('eleves')->latest('id')->first();
            $nouveauNumero = $dernierEleve ? $dernierEleve->id + 1 : 1;
            // Générer un code avec 6 chiffres formatés (ex: 000001)
            $code = str_pad($nouveauNumero, 3, '0', STR_PAD_LEFT);

            $eleve->nom = $request->nom;
            $eleve->prenom = $request->prenom;
            $eleve->telephoneParent = $request->telephone;
            $eleve->matricule = 'MAT' . $code ;
            $eleve->user_id =  auth()->id() ?? 2;



            $eleve->save();

            return back()->with('success_message', 'Enregistrement éffectué avec succès');
            
        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

     /**
     * Editer un éléve
     */
     public function update(Eleve $eleve, Request $request){

        $exists = Eleve::where('id', '!=', $eleve->id)
            ->where('nom', $request->nom)
            ->where('prenom', $request->prenom)
            ->exists();

        if ($exists) {
            return back()->with('error_message', 'Un élève avec les mêmes informations existe déjà.');
        }

        try {

            $eleve->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephoneParent' => $request->telephone,
            ]);

            return back()->with('success_message', ' Les informations de l\'élève ont été modifiés avec succès.');
        } catch (\Exception $e) {
           
            return back()->with('error_message', 'Une erreur est survenue pendant la modification.');
        }
    }



    /**
     * Supprimer un eleve
     */
    public function delete(Eleve $eleve)
    {
        try {
            $eleve->delete();
            return back()->with('success_message', 'L\'élève a été supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }
}
