@extends('layout.master')

@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">

                <div class="card-body">
                    <button type="button" class="btn  btn-outline-primary" data-toggle="modal" data-target="#basicModal"><i class="fas fa-plus"></i> Nouvel enseignant</button>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                        <div class="card-header">
                            <h4>Liste de tout les enseignants</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Téléphone</th>
                                    <th>Salaire Mensuel</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enseignants as $enseignant )
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $enseignant->matricule }}</td>
                                            <td>{{ $enseignant->nom }}  {{ $enseignant->prenom }}</td>
                                            <td>{{ $enseignant->telephone }}</td>
                                            <td>{{ number_format($enseignant->salaire ?? '0', 0, ',', '.') }} F</td>
                                            <td>

                                                <a href="{{ route('enseignant.show', ['enseignant' => $enseignant['id']]) }}" class="btn-sm btn-info m-1" href="#">
                                                    <i class="fas fa-eye"></i> Détails
                                                </a>
                                                <a class="btn-sm btn-warning m-3" href="#" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}">
                                                    <i class="fas fa-edit"></i> Editer
                                                </a>
                                                @if(auth()->user()->role_id== 1)
                                                    <form action="{{ route('enseignant.delete', ['enseignant' => $enseignant->id]) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ?')">
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

    {{-- Modal pour ajouter --}}

    <!-- basic modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enregistré un nouvel enseignant </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{ route('enseignant.store') }}" method="post">
                    @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Prénoms</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="number" min="0" name="telephone" class="form-control" required>
                    </div>

                        <div class="form-group">
                        <label>Salaire</label>
                        <input type="number" min="0" name="salaire" class="form-control" required>
                    </div>

                </div>
                
                <div class="modal-footer bg-whitesmoke br">
                    <button type="submite" class="btn btn-primary">Enregistré</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modifier classe --}}
    @foreach ($enseignants as $enseignant)
        <div class="modal fade" id="editEntry{{ $loop->iteration }}">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editer les informations de l'enseignant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="settings-form" action="{{ route('enseignant.update', ['enseignant'=>$enseignant->id]) }}" method="POST">
                            @csrf
                            @method('PUT')


                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" class="form-control" value="{{ $enseignant->nom }}"  required>
                            </div>

                            <div class="form-group">
                                <label>Prénoms</label>
                                <input type="text" name="prenom" value="{{ $enseignant->prenom }}"  class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="number" min="0" name="telephone" value="{{ $enseignant->telephone }}"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Salaire</label>
                                <input type="number" min="0" name="salaire" value="{{ $enseignant->salaire }}"  class="form-control">
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Modifier</button>
                            </div>
                        </form>
                    </div>
                
                </div>
            </div>
        </div>
    @endforeach

@endsection