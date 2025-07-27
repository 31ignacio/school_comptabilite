@extends('layout.master')

@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">

                <div class="card-body">
                    <button type="button" class="btn  btn-outline-primary" data-toggle="modal" data-target="#basicModal"><i class="fas fa-plus"></i> Inscrit un élève dans une classe</button>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Liste des écoliers de la Maternelle I</h4>
                            </div>

                            <div class="card-body">
                                {{-- Filtres --}}
                                <form action="{{ route('inscription.index') }}" method="GET">
                                    <div class="row mb-4">
                                   
                                    @csrf
                                    <div class="col-md-5">
                                        <label for="filterClasse">Filtrer par classe</label>
                                        <select id="filterClasse" class="form-control" name="classe_id">
                                            <option value="">-- Toutes les classes --</option>
                                            @foreach($classes as $classe)
                                                <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                                                    {{ $classe->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="filterAnnee">Filtrer par année scolaire</label>
                                        <select id="filterAnnee" class="form-control" name="annee_id">
                                            <option value="">-- Toutes les années --</option>
                                            @foreach($annees as $annee)
                                                <option value="{{ $annee->id }}" {{ $anneeId == $annee->id ? 'selected' : '' }}>
                                                    {{ $annee->annee }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                     <div class="col-md-2 mt-4">
                                        <button class="btn btn-outline-success" type="submit">
                                            <i class="fas fa-search"></i> Filtre

                                        </button>                                        
                                    </div>
                                </div>
                                </form>


                                {{-- Tableau --}}
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>Élève</th>
                                                <th>Classe</th>
                                                <th>Montant Payé</th>
                                                <th>Scolarité Totale</th>
                                                <th>Reste à Payer</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inscriptions as $inscription)
                                                <tr>
                                                    <td>{{ $inscription['eleve']->nom }} {{ $inscription['eleve']->prenom }}</td>
                                                    <td>{{ $inscription['classe'] }}</td>
                                                    <td>{{ number_format($inscription['montantPaye'] ?? '0', 0, ',', '.') }} F</td>
                                                    <td>{{ number_format($inscription['scolariteTotale'] ?? '0', 0, ',', '.') }} F</td>
                                                   <td class="
                                                        @if($inscription['resteAPayer'] == 0) bg-success text-white
                                                        @elseif($inscription['resteAPayer'] > 0 && $inscription['resteAPayer'] <= ($inscription['scolariteTotale'] * 0.3)) bg-warning text-dark
                                                        @else bg-danger text-white
                                                        @endif">
                                                        {{ number_format($inscription['resteAPayer'] ?? '0', 0, ',', '.') }} F
                                                    </td>
                                                    <td class="
                                                        @if($inscription['solde'] == 'Soldé') bg-success text-white
                                                        @else bg-danger text-white
                                                        @endif">
                                                        {{ $inscription['solde'] }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('inscription.show', ['inscription' => $inscription['id']]) }}" class="btn-sm btn-info m-1">
                                                            <i class="fas fa-eye"></i> Détails
                                                        </a>
                                                        <button class="btn-sm btn-warning m-1" href="#" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}">
                                                            <i class="fas fa-edit"></i> Éditer
                                                        </button>
                                                        @if(auth()->user()->role_id== 1)
                                                        <form action="{{ route('inscription.delete', ['inscription' => $inscription['id']]) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-sm btn-danger m-1" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ?')">
                                                                <i class="fas fa-trash-alt"></i> Supprimer
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach               
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
                <h5 class="modal-title" id="exampleModalLabel">Inscrire un ou plusieurs élèves</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('inscription.store') }}" method="POST">
                @csrf
                <div class="modal-body">

                <div class="form-group">
                    <label for="eleves">Élèves</label>
                    <select id="eleves" class="form-control select2" name="eleves[]" multiple="multiple" required style="width: 100%;">
                        @forelse ($elevesNonInscrits as $eleve)
                        <option value="{{ $eleve->id }}">{{ $eleve->nom }} {{ $eleve->prenom }}</option>
                        @empty
                        <option disabled>Aucun élève disponible</option>
                        @endforelse
                    </select>
                </div>


                <div class="form-group">
                    <label>Classe</label>
                    <select class="form-control" name="classe_id" required>
                    <option value="">-- Choisir une classe --</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Année scolaire</label>
                    <select class="form-control" name="annee_id" required>
                   
                        <option value="{{ $derniereAnne->id }}">{{ $derniereAnne->annee }}</option>
                    </select>
                </div>

                </div>

                <div class="modal-footer bg-whitesmoke br">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
            </div>
        </div>
    </div>


    {{-- Modifier classe --}}
    {{-- @foreach ($eleves as $eleve)
        <div class="modal fade" id="editEntry{{ $loop->iteration }}">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editer les informations d'un élève</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                <form class="settings-form" action="{{ route('eleve.update', ['eleve'=>$eleve->id]) }}" method="POST">
                    @csrf
                    @method('PUT')


                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" value="{{ $eleve->nom }}"  required>
                    </div>

                    <div class="form-group">
                        <label>Prénoms</label>
                        <input type="text" name="prenom" value="{{ $eleve->prenom }}"  class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone d'un parent</label>
                        <input type="number" min="0" name="telephone" value="{{ $eleve->telephoneParent }}"  class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
                </div>
            
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        </div>
    @endforeach --}}

@endsection