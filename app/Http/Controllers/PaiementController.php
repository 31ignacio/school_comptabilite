<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Paiement;
use Exception;
//use PDF;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class PaiementController extends Controller
{
    

    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:1',
        ]);

        try {
            // Récupération de l'inscription
            $inscription = Inscription::with('classe')->findOrFail($request->inscription);

            // Somme déjà payée
            $somme = Paiement::where('inscription_id', $request->inscription)->sum('montantPayer');

            // Reste à payer
            $resteAPayer = $inscription->classe->scolarite - $somme;

            // Vérification
            if ($request->montant <= $resteAPayer) {
                Paiement::create([
                    'montantPayer' => $request->montant,
                    'inscription_id' => $request->inscription,
                    'ResteAPayer' => $resteAPayer - $request->montant,
                    'user_id' => auth()->id() // adapte selon ton auth
                ]);

                return redirect()->back()->with('success_message', 'Paiement enregistré avec succès.');
            } else {
                return redirect()->back()->with('error_message', 'Le montant saisi est supérieur à la scolarité restante.');
            }

        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }


    public function annuler(Request $request)
    {
        $paiement= Paiement::where('id',$request->id)->first();
        // foreach ($factures as $facture) {
        //     //c'est la tu feras le jeu
        //     $produit = grosProduit::where('libelle', $facture->produit)->first();

        //     if ($produit) {
        //         $nouvelleQuantite = $produit->quantite + $facture->quantite - $facture->quantite; // Mettez à jour la nouvelle quantité
        
        //         // Assurez-vous de mettre à jour le produit avec la nouvelle quantité correcte
        //         $produit->quantite = $nouvelleQuantite;
        //         $produit->save();
        //     }
        // }
        $paiement->delete();

        return back()->with('success_message', 'Le paiement a été annulé avec succès.');
    }

    /**
     * Imprimer une quittance
     */
    public function download(Paiement $paiement)
    {
        try {

            $fullPaymentInfo = Paiement::find($paiement->id);

            $nom=$fullPaymentInfo->inscription->eleve->nom;
            $prenom=$fullPaymentInfo->inscription->eleve->prenom;
            $classe=$fullPaymentInfo->inscription->classe->nom;
            $annee=$fullPaymentInfo->inscription->anneScolaire->annee;

            $MontantPayer = Paiement::where('inscription_id', $fullPaymentInfo->inscription->id)->sum('montantPayer');

            $pdf = PDF::loadView('facture', compact('fullPaymentInfo','MontantPayer'));
            $nomFichier = $annee . '_' . $classe . '_' . $nom . '_' . $prenom . '.pdf';
            return $pdf->download($nomFichier);
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

}
