<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quittance de Paiement</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        #quittance {
            width: 600px;
            background: #ffffff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
        }

        #header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        #header b {
            font-size: 20px;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        #signature {
            text-align: center;
            margin-top: 20px;
        }

        #owner-info {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div id="quittance">
        <div id="header">
            <b>Complexe Scolaire St Mathieu</b>
            <p>Adresse: Tamkpè, Abomey-Calavi | Tél: 01 65 32 14 78</p>
        </div>

        <table>
            <tr>
                <th>Enseignant</th>
                <td><i>{{$fullPaymentInfo->enseignant->nom}} {{$fullPaymentInfo->enseignant->prenom}}</i></td>
            </tr>
            <tr>
                <th>Mois/Année</th>
                <td>{{$fullPaymentInfo->mois}}/{{$fullPaymentInfo->annee}}</td>
            </tr>
            
            <tr>
                <th>Montant Payé</th>
                <td><b>{{ number_format($fullPaymentInfo->montant, 0, ',', ' ') }} FCFA</b></td>
            </tr>
            {{-- <tr>
                <th>Reste à Payer</th>
                <td><b>{{ number_format($fullPaymentInfo->ResteAPayer, 0, ',', ' ') }} FCFA</b></td>
            </tr>   --}}
            <tr>
                <th>Date Paiement</th>
                <td>{{ \Carbon\Carbon::parse($fullPaymentInfo->created_at)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Remarque</th>
                <td>
                    <pre>{{$fullPaymentInfo->remarque}}</pre>
                </td>
            </tr>
        </table>

        <div id="signature">
            <img src="qr.png" alt="QR" style="width: 100px;">
        </div>

        <div id="owner-info">
            <b>Comptable</b>
            <p>Nom: <b>{{$fullPaymentInfo->user->name}}</b></p>
            <p>Tél: {{$fullPaymentInfo->user->telephone}}</p>
        </div>
    </div>

</body>
</html>
