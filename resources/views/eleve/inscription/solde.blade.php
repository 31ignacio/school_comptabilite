@extends('layout.master')

@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">


       <form method="GET" action="{{ route('solde.index') }}">
    <label for="classe_id">Classe :</label>
    <select name="classe_id">
        @foreach($classes as $classe)
            <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                {{ $classe->nom }}
            </option>
        @endforeach
    </select>

    <label for="annee_id">Année :</label>
    <select name="annee_id">
        @foreach($annees as $annee)
            <option value="{{ $annee->id }}" {{ $anneeId == $annee->id ? 'selected' : '' }}>
                {{ $annee->annee }}
            </option>
        @endforeach
    </select>

    <button type="submit">Filtrer</button>
</form>


<table>
    <thead>
        <tr>
            <th>Élève</th>
            <th>Classe</th>
            <th>Montant Payé</th>
            <th>Scolarité Totale</th>
            <th>Reste à Payer</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inscriptions as $inscription)
            <tr>
                <td>{{ $inscription['eleve']->nom }} {{ $inscription['eleve']->prenom }}</td>
                <td>{{ $inscription['classe'] }}</td>
                <td>{{ $inscription['montantPaye'] }}</td>
                <td>{{ $inscription['scolariteTotale'] }}</td>
                <td>{{ $inscription['resteAPayer'] }}</td>
                <td>{{ $inscription['solde'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>



            </div>
        </section>
    </div>
@endsection