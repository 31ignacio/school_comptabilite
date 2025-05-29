<?php

namespace App\Http\Controllers;

use App\Models\AnneScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Paiement;
use Exception;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    //

    /**
     * Afficher la liste des eleves
    */
    // public function index()
    // {
    //     // 1. Dernière année scolaire
    //     $derniereAnnee = AnneScolaire::orderByDesc('created_at')->first();

    //     // 2. Inscriptions pour la classe 1 et la dernière année
    //     $inscriptions = Inscription::where('classe_id', 1)
    //     ->where('annee_id', $derniereAnnee->id)
    //     ->orderBy('created_at', 'desc')
    //     ->get();

    //     // 2. ID des élèves déjà inscrits pour cette année
    //     $elevesInscrits = Inscription::where('annee_id', $derniereAnnee->id)
    //         ->pluck('eleve_id')
    //         ->toArray();
    //     // 3. Élèves non inscrits
    //     $elevesNonInscrits = Eleve::whereNotIn('id', $elevesInscrits)->get();
    //     $classes = Classe::all();
    //     $annees = AnneScolaire::all();

    //     return view('eleve.inscription.index', compact('inscriptions', 'classes', 'annees','derniereAnnee','elevesNonInscrits'));
    // }


    public function index(Request $request){

          // Récupération des paramètres de filtrage
        $classeId = $request->input('classe_id', 1);
        $anneeId = $request->input('annee_id');

        // Récupération des années et classes disponibles
        $classes = Classe::all(); // Ajout pour récupérer toutes les classes
        $annees = AnneScolaire::orderBy('annee', 'desc')->get(); // Toutes les années disponibles
        $derniereAnne = AnneScolaire::orderBy('annee', 'desc')->first();

        // Détermination de l'année à utiliser
        if (!$anneeId) {
            $derniereAnnee = AnneScolaire::orderBy('annee', 'desc')->first();
            $anneeId = $derniereAnnee->id;
        }

        // Récupération des inscriptions et calcul du solde
        $inscriptions = Inscription::where('annee_id', $anneeId)
            ->whereHas('classe', function ($query) use ($classeId) {
                $query->where('id', $classeId);
            })
            ->with(['eleve', 'classe'])
            ->get()
            ->map(function ($inscription) {
                $montantPaye = Paiement::where('inscription_id', $inscription->id)->sum('montantPayer');
                $scolariteTotale = $inscription->classe->scolarite;
                $solde = $montantPaye >= $scolariteTotale;

                return [
                    'id' => $inscription->id,
                    'eleve' => $inscription->eleve,
                    'classe' => $inscription->classe->nom,
                    'montantPaye' => $montantPaye,
                    'scolariteTotale' => $scolariteTotale,
                    'resteAPayer' => $scolariteTotale - $montantPaye,
                    'solde' => $solde ? 'Soldé' : 'Non soldé',
                ];
            });

         // 2.m ID des élèves déjà inscrits pour cette année
        $elevesInscrits = Inscription::where('annee_id', $derniereAnne->id)
            ->pluck('eleve_id')
            ->toArray();
        // 3. Élèves non inscrits
        $elevesNonInscrits = Eleve::whereNotIn('id', $elevesInscrits)->get();

    return view('eleve.inscription.index', compact('inscriptions', 'classeId', 'anneeId', 'classes', 'annees','elevesNonInscrits','derniereAnne'));

    }


    public function store(Request $request)
    {

        $request->validate([
            'eleves' => 'required|array',
            'eleves.*' => 'exists:eleves,id',
            'classe_id' => 'required|exists:classes,id',
            'annee_id' => 'required|exists:anne_scolaires,id',
        ]);

        try {
             
            foreach ($request->eleves as $eleve_id) {

                $existe = Inscription::where('eleve_id', $eleve_id)
                    ->where('classe_id', $request->classe_id)
                    ->where('annee_id', $request->annee_id)
                    ->exists();

                if ($existe) {

                     return back()->with('error_message', "L’élève ID $eleve_id est déjà inscrit dans cette classe pour cette année.");
                }
                
                Inscription::create([

                    'eleve_id' => $eleve_id,
                    'classe_id' => $request->classe_id,
                    'annee_id' => $request->annee_id,
                    'user_id' =>  auth()->id() ?? 2,
                    'statut'=> "Reste à soldé"

                ]);
            }
                return redirect()->back()->with('success_message', 'Inscription(s) enregistrée(s) avec succès.');

        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }


    public function show($id){

        $inscription= Inscription::where('id',$id)->first();
        $paiements= Paiement::where('inscription_id',$id)->orderBy('created_at', 'desc')->get();
        $MontantPayer = Paiement::where('inscription_id', $id)->sum('montantPayer');
        
        return view('eleve.inscription.show', compact('inscription','paiements','id','MontantPayer'));
    }


    
    /**
     * Supprimer un eleve
     */
    public function delete(Inscription $inscription)
    {
        try {
            $inscription->delete();
            return back()->with('success_message', 'L\'élève a été supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }


}
