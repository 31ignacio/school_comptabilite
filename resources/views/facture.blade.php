<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quittance</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 280px;
            padding: 10px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .info {
            margin: 4px 0;
        }

        .right {
            text-align: right;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt">
        <div class="center bold">
            COMPLEXE SCOLAIRE ST MATHIEU<br>
            -----------------------------<br>
            Quittance de Paiement
        </div>

        <div class="line"></div>

        <div class="info">Nom Élève : {{ $fullPaymentInfo->inscription->eleve->nom }} {{ $fullPaymentInfo->inscription->eleve->prenom }}</div>
        <div class="info">Classe : {{ $fullPaymentInfo->inscription->classe->nom }}</div>
        <div class="info">Année : {{ $fullPaymentInfo->inscription->anneScolaire->annee }}</div>
        <div class="info">Date : {{ \Carbon\Carbon::parse($fullPaymentInfo->created_at)->format('d/m/Y') }}</div>

        <div class="line"></div>

        <div class="info">Montant Payé :</div>
        <div class="info right">{{ number_format($fullPaymentInfo->montantPayer, 0, ',', ' ') }} FCFA</div>

        <div class="info">Reste à Payer :</div>
        <div class="info right">{{ number_format($fullPaymentInfo->ResteAPayer, 0, ',', ' ') }} FCFA</div>

        <div class="info">Statut :
            @if($fullPaymentInfo->inscription->classe->scolarite == $MontantPayer)
                <span class="bold">Soldé</span>
            @else
                <span class="bold">Reste à solder</span>
            @endif
        </div>

        <div class="line"></div>

        <div class="info">Comptable : {{ $fullPaymentInfo->user->name }}</div>
        <div class="info">Tél : {{ $fullPaymentInfo->user->telephone }}</div>

        <div class="center" style="margin-top:10px;">Merci pour votre paiement</div>
        <div class="center">***********************</div>
    </div>

</body>
</html>
