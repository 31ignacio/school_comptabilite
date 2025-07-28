@extends('layout.master')

@section('content')


    <div class="main-content">
        <section class="section">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('accueil.index') }}"><i class="fas fa-tachometer-alt"></i> Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enseignant.index') }}"><i class="far fa-file"></i> Liste des enseignants</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i> Détails</li>
                </ol>
            </nav>
            <div class="section-body">
                <div class="row">

                    {{-- Colonne gauche : Infos de l'élève --}}
                    <div class="col-md-4 col-lg-12 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $enseignant->nom ?? '-' }} {{ $enseignant->prenom ?? '-' }}</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Date de création : </strong>{{ \Carbon\Carbon::parse($enseignant->created_at)->format('d/m/Y') }}</li>
                                    <li class="list-group-item"><strong>Matricule : </strong> {{ $enseignant->matricule ?? '-' }} </li>
                                    <li class="list-group-item"><strong>Téléphone : </strong> {{ $enseignant->telephone ?? '-' }}</li>
                                    <li class="list-group-item"><strong>Salaire Mensuel : </strong> <span class="badge badge-warning">{{ number_format($enseignant->salaire ?? '0', 0, ',', '.') }} F</span></li>
                                    <li class="list-group-item"><strong>Total salaire perçu : </strong> <span class="badge badge-success">{{ number_format($somme  ?? '0', 0, ',', '.') }} F</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Colonne droite : Historique des paiements --}}
                    
                    <div class="col-md-8 col-lg-12 col-xl-8">
                        <div class="card-body">
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#basicModal"><i class="fas fa-plus"></i> Enregistrer un paiement de salaire</button>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Historique des paiements de salaire</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                    {{-- <table class="table table-hover" style="width:100%;"> --}}
                                        <thead>
                                            <tr>
                                                {{-- <th>#</th> --}}
                                                <th>Date Paiement</th>
                                                <th>Mois / Année</th>
                                                <th>Montant payé</th>
                                                <th>Comptable</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($paiements as $index => $paiement)
                                                <tr>
                                                    {{-- <td>{{ $index + 1 }}</td> --}}
                                                    <td>{{ \Carbon\Carbon::parse($paiement->created_at)->format('d/m/Y') }}</td>
                                                    <td>{{ $paiement->mois }} / {{ $paiement->annee }}</td>
                                                    <td>{{ number_format($paiement->montant ?? '0', 0, ',', '.') }}</td>
                                                    <td>{{ $paiement->user->name }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-outline-info rounded-pill m-2"
                                                            href="{{ route('paiement.enseignant.download', $paiement->id) }}"
                                                            title="Imprimer la quittance">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        @if(auth()->user()->role_id== 1)
                                                        <a href="#" class="btn btn-sm btn-outline-danger rounded-pill m-2" title="Annuler la quittance" data-toggle="modal"
                                                            data-target="#confirmationModal" onclick="updateModal('{{ $paiement->id }}')">
                                                            <i class="fas fa-times-circle me-1"></i>
                                                        </a>
                                                        @endif
                                                        
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Aucun paiement enregistré.</td>
                                                </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!-- Modal d'inscription d'un élève -->
    <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enregistré un paiement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('paiement.enseignant.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Mois</label>
                                <select name="mois" class="form-control" required>
                                    @php
                                        $mois_francais = [
                                            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 
                                            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 
                                            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                                        ];
                                        $mois_actuel = date('n') - 1;
                                        if ($mois_actuel < 1) $mois_actuel = 12;
                                    @endphp
                                    @foreach($mois_francais as $num => $nom)
                                        <option value="{{ $nom }}" {{ $num == $mois_actuel ? 'selected' : '' }}>{{ $nom }}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Année</label>
                                <select name="annee" class="form-control" required>
                                    @php
                                        $annee_actuelle = date('Y');
                                        $annees_disponibles = range(2024, 2035);
                                    @endphp
                                    @foreach($annees_disponibles as $annee)
                                        <option value="{{ $annee }}" {{ $annee == $annee_actuelle ? 'selected' : '' }}>{{ $annee }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Montant</label>
                                <input type="number" min="0" name="montant" class="form-control" required>
                            </div>
                        </div>

                        <input type="hidden" name="inscription" value="{{ $id }}" class="form-control" required>

                        <div class="col-md-12">
                            <label>Remarque</label>
                            <textarea name="remarque" class="form-control" rows="3" placeholder="Ajoutez vos commentaires ici..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     {{-- Modal pour annuler un paiement --}}
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="get" action="{{ route('paiement.enseignant.annuler') }}">
                    @csrf
                    <div class="modal-body">
                        Voulez-vous annuler ce paiement ?
                    </div>
                    <input type="hidden" id="id" name="id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                        <button type="submit" class="btn btn-danger">Oui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateModal(id) {
            
            // Mettez à jour le contenu du span avec le code spécifique
            document.getElementById('id').value = id;
        }
    </script>

@endsection
