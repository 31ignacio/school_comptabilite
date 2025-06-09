<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\PaiementEnseignant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnseignantController extends Controller
{
    //

    /**
     * Afficher la liste des enseignants
    */
    public function index(){
        
        $enseignants = Enseignant::orderBy('created_at', 'desc')->get();
      
        return view('enseignant.index',compact('enseignants'));
    }


     public function show($id , Request $request){

        $enseignant= Enseignant::where('id',$id)->first();
        $paiements= PaiementEnseignant::where('enseignant_id',$id)->orderBy('created_at', 'desc')->get();
         // Somme déjà payée
        $somme = PaiementEnseignant::where('enseignant_id', $id)->sum('montant');
        
        return view('enseignant.show',compact('id','paiements','enseignant','somme'));
    }

    
    /**
     * Enregistrer un nouvel enseignant
    */
    public function store(Enseignant $enseignant,Request $request)
    {
      
        try 
        {
            $exists = Enseignant::where('id', '!=', $enseignant->id)
            ->where('nom', $request->nom)
            ->where('prenom', $request->prenom)
            ->where('telephone', $request->telephone)
            ->exists();

            if ($exists) {
                return back()->with('error_message', 'Un enseignant avec les mêmes informations existe déjà.');
            }

            $dernierEseignant = DB::table('enseignants')->latest('id')->first();
            $nouveauNumero = $dernierEseignant ? $dernierEseignant->id + 1 : 1;
            // Générer un code avec 6 chiffres formatés (ex: 000001)
            $code = str_pad($nouveauNumero, 3, '0', STR_PAD_LEFT);

            $enseignant->nom = $request->nom;
            $enseignant->prenom = $request->prenom;
            $enseignant->telephone = $request->telephone;
            $enseignant->salaire = $request->salaire;
            $enseignant->matricule = 'PROF' . $code ;
            $enseignant->user_id =  auth()->id() ?? 2;
            $enseignant->save();

            return back()->with('success_message', 'Enregistrement éffectué avec succès');
            
        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

     /**
     * Editer un enseignant
     */
     public function update(Enseignant $enseignant, Request $request){

        $exists = Enseignant::where('id', '!=', $enseignant->id)
            ->where('nom', $request->nom)
            ->where('prenom', $request->prenom)
            ->where('telephone', $request->telephone)
            ->exists();

        if ($exists) {
            return back()->with('error_message', 'Un enseignant avec les mêmes informations existe déjà.');
        }

        try {

            $enseignant->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'salaire' => $request->salaire,

            ]);

            return back()->with('success_message', ' Les informations de l\'enseignant ont été modifiés avec succès.');
        } catch (\Exception $e) {
           
            return back()->with('error_message', 'Une erreur est survenue pendant la modification.');
        }
    }



    /**
     * Supprimer un enseignant
     */
    public function delete(Enseignant $enseignant)
    {
        try {
            $enseignant->delete();
            return back()->with('success_message', 'L\'enseignant a été supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }
}
