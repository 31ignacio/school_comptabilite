<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\PaiementEnseignant;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class PaiementEnseignantController extends Controller
{
    

    public function store(Request $request)
    {
        $request->validate([
            'mois' => 'required',
            'annee' => 'required|numeric|min:1',
        ]);

        try {
            // Vérifier s’il existe déjà un paiement pour cet enseignant ce mois et cette année
            $existe = PaiementEnseignant::where('enseignant_id', $request->inscription)
                ->where('mois', $request->mois)
                ->where('annee', $request->annee)
                ->exists();

            if ($existe) {
                return redirect()->back()->with('error_message', 'Un paiement a déjà été effectué pour cet enseignant ce mois-ci.');
            }

            // Récupérer le salaire
            $salaire = Enseignant::find($request->inscription)?->salaire;

            if (!$salaire) {
                return redirect()->back()->with('error_message', 'Salaire introuvable pour cet enseignant.');
            }

            // Vérification du montant
            if ($request->montant <= $salaire) {
                PaiementEnseignant::create([
                    'montant' => $salaire,
                    'enseignant_id' => $request->inscription,
                    'mois' => $request->mois,
                    'annee' => $request->annee,
                    'remarque' => $request->remarque,
                    'user_id' => auth()->id(),
                ]);

                return redirect()->back()->with('success_message', 'Paiement enregistré avec succès.');
            } else {
                return redirect()->back()->with('error_message', 'Le montant saisi est supérieur au salaire.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }



    public function annuler(Request $request)
    {
        $paiement= PaiementEnseignant::where('id',$request->id)->first();
        
        $paiement->delete();

        return back()->with('success_message', 'Le paiement a été annulé avec succès.');
    }


    /**
     * Imprimer une quittance
     */
    public function download(PaiementEnseignant $paiement)
    {
        try {

            $fullPaymentInfo = PaiementEnseignant::find($paiement->id);

            $nom=$fullPaymentInfo->enseignant->nom;
            $prenom=$fullPaymentInfo->enseignant->prenom;
            $mois=$fullPaymentInfo->mois;
            $annee=$fullPaymentInfo->annee;

            $pdf = PDF::loadView('enseignant.facture', compact('fullPaymentInfo'));
            $nomFichier = $mois . '_' . $annee . '_' . $nom . '_' . $prenom . '.pdf';
            return $pdf->download($nomFichier);
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

}
