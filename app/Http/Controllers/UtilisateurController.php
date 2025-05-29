<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    //

     public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $roles=Role::all();

        return view('user.index', compact('users','roles'));
    }


    /**
     * Enregistrer un user
    */
    public function store(User $user,Request $request)
    {
      
        try {
            $exists = user::where('id', '!=', $user->id)
            ->where('name', $request->name)
            ->where('email', $request->email)
            ->exists();

            if ($exists) {
                return back()->with('error_message', 'Un utilisateur avec les mêmes informations existe déjà.');
            }

            $user->name = $request->nom;
            $user->email = $request->email;
            $user->telephone = $request->telephone;
            $user->role_id= $request->role;
            $user->estActif= 0;
            $user->password =Hash::make($request->password);

            $user->save();

            return back()->with('success_message', 'Utilisateur ajoutée avec succès');
            
        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

    /**
     * Editer un user
     */
    public function update(User $user, Request $request)
    {
        //Enregistrer un nouveau département
        try {
            $user->name = $request->nom;
            $user->email = $request->email;
            $user->telephone = $request->telephone;
            $user->role_id = $request->role;
            $user->estActif= 0;

            //dd($user);

            $user->update();

            return back()->with('success_message', 'Utilisateur mis à jour avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }


     /**
     * Supprimer un user
     */
    public function delete(User $user)
    {
        try {
            $user->delete();
            return back()->with('success_message', 'Utilisateur supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }
}
