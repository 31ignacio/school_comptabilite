@extends('layout.master')

@section('content')


<div class="main-content">
    <section class="section">
        <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt"></i> Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inscription.index') }}"><i class="far fa-file"></i> Liste des élèves par classe</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i> Détails</li>
                      </ol>
                    </nav>
        <div class="section-body">
            <div class="row">

                {{-- Colonne gauche : Infos de l'élève --}}
                <div class="col-md-4 col-lg-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $inscription->eleve->nom ?? '-' }} {{ $inscription->eleve->prenom ?? '-' }}</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                {{-- <li class="list-group-item"><strong>Nom :</strong> {{ $inscription->eleve->nom ?? '-' }} {{ $inscription->eleve->prenom ?? '-' }}</li> --}}
                                <li class="list-group-item"><strong>Matricule :</strong> {{ $inscription->eleve->matricule ?? '-' }} </li>
                                <li class="list-group-item"><strong>Classe :</strong> {{ $inscription->classe->nom ?? '-' }}</li>
                                <li class="list-group-item"><strong>Année scolaire :</strong> {{ $inscription->anneScolaire->annee ?? '-' }}</li>
                                <li class="list-group-item"><strong>Téléphone Parent :</strong> {{ $inscription->eleve->telephoneParent ?? '-' }}</li>
                                <li class="list-group-item"><strong>Scolarité :</strong> {{ number_format($inscription->classe->scolarite, 0, ',', '.') }} F</li>
                                <li class="list-group-item"><strong>Montant Payé :</strong> {{ number_format($MontantPayer ?? '0', 0, ',', '.') }} F</li>
                                <li class="list-group-item"><strong>Reste à payer :</strong> {{ number_format($inscription->classe->scolarite - $MontantPayer  ?? '0', 0, ',', '.') }} F</li>

                                <li class="list-group-item"><strong>Statut :</strong> @if($inscription->classe->scolarite == $MontantPayer )
                                                            <b class="text-success">Soldé</b>
                                                        @else
                                                            <b class="text-danger">Reste à soldé</b>
                                                        @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Colonne droite : Historique des paiements --}}
                
                <div class="col-md-8 col-lg-12 col-xl-8">
                    <div class="card-body">
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#basicModal"><i class="fas fa-plus"></i> Enregistrer un paiement</button>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Historique des paiements</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Montant payé</th>
                                            <th>Reste à payé</th>
                                            <th>Comptable</th>
                                            <th>Annuler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($paiements as $index => $paiement)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($paiement->created_at)->format('d/m/Y') }}</td>
                                                <td>{{ number_format($paiement->montantPayer, 0, ',', ' ') }}</td>
                                                <td>{{ number_format($paiement->ResteAPayer, 0, ',', ' ') }} F</td>
                                                <td>{{ $paiement->user->name }}</td>
                                                <td>
                                                    @if ($index === 0)
                                                        <a href="#" class="btn-sm btn-danger" data-toggle="modal"
                                                            data-target="#confirmationModal"
                                                            onclick="updateModal('{{ $paiement->id }}')">
                                                            <i class="fas fa-times-circle me-1"></i> Annuler
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

            <form action="{{ route('paiement.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Montant</label>
                        <input type="number" min="0" name="montant" class="form-control" required>
                    </div>
                    <input type="hidden" name="inscription" value="{{ $id }}" class="form-control" required>
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
                    <form method="get" action="{{ route('paiement.annuler') }}">
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
