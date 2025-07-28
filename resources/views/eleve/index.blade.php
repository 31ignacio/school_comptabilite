@extends('layout.master')

@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">

                 <div class="card-body">
                    <button type="button" class="btn  btn-outline-primary" data-toggle="modal" data-target="#basicModal"><i class="fas fa-plus"></i> Nouvel élève</button>
                  </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                        <div class="card-header">
                            <h4>Liste de tout les élèves</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Téléphone parent</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleves as $eleve )
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $eleve->matricule }}</td>
                                            <td>{{ $eleve->nom }}  {{ $eleve->prenom }}</td>
                                            <td>{{ $eleve->telephoneParent }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-warning rounded-pill m-2" title="Editer cet élève" href="#" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(auth()->user()->role_id== 1)
                                                    <form action="{{ route('eleve.delete', ['eleve' => $eleve->id]) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill m-2" title="Supprimet cet élève" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ?')">
                                                            <i class="fas fa-trash-alt"></i>
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
        <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enregistré un nouvel élève </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{ route('eleve.store') }}" method="post">
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
                        <label>Téléphone d'un parent</label>
                        <input type="number" min="0" name="telephone" class="form-control" required>
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
        @foreach ($eleves as $eleve)
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
        @endforeach

@endsection