<?php

namespace App\Http\Controllers;

use App\Models\AnneScolaire;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccueilController extends Controller
{
    
    public function index()
    {

        // Récupérer l'année la plus récente
        $annee = AnneScolaire::orderBy('id', 'desc')->first();

        if ($annee) {
            $nombreEleves = Inscription::where('annee_id', $annee->id)->count();
        } else {
            $nombreEleves = 0; // Aucune année enregistrée
        }
        
        return view('accueil.index',compact('nombreEleves','annee'));
    }


    
    /**
     * Afficher le profil d" l'utilisateur connecté
     */
    public function profil(){

        // Récupérer l'utilisateur connecté
        $user = Auth::user()->id;
        $user = User::where('id', $user)->first();
        
       return view('accueil.profil',compact('user'));
    }


    /**
     * Modifier le password de l'utilisateur connecté
     */
    public function updatePassword(Request $request){

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ], [
            'old_password.required' => 'L\'ancien mot de passe est requis.',
            'password.required' => 'Le nouveau mot de passe est requis.',
            'password.min' => 'Le nouveau mot de passe doit comporter au moins 5 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password_confirmation.required' => 'La confirmation du mot de passe est requise.'
        ]);
        
        try{
            $user = Auth::user();
            // Vérifier si le mot de passe actuel est correct
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->with('error_message','Le mot de passe actuel est incorrect.');
            }
        
            // Vérifier si le nouveau mot de passe est identique à l'ancien
            if (Hash::check($request->password, $user->password)) {
                return back()->with('error_message','Le nouveau mot de passe doit être différent du mot de passe actuel.');
            }

            // Vérifier si le mot de passe et la confirmation sont identiques
            if ($request->password !== $request->password_confirmation) {
                return back()->with('error_message','Le mot de passe de confirmation ne correspond pas au nouveau mot de passe.');
            }
        
            // Mettre à jour le mot de passe
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            //Mail::to($user->email)->send(new ModifierPasswordMail($user));

            // Déconnecter l'utilisateur
            Auth::logout();

            // Rediriger vers la page de connexion avec un message de succès
            return redirect()->route('login')->with('success_message', 'Votre mot de passe a été modifié avec succès. Veuillez vous reconnecter avec le nouveau mot de passe.');

        }catch(Exception $e){
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');

        }
    }

}
