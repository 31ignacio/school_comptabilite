@extends('layout.master')

@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">

                 <div class="card-body">
                    <button type="button" class="btn  btn-outline-primary" data-toggle="modal" data-target="#basicModal"><i class="fas fa-plus"></i> Ajouter une classe</button>
                  </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                        <div class="card-header">
                            <h4>Liste des classes</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Classe</th>
                                    <th>Solarité</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classes as $classe )
                                        
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $classe->nom }}</td>
                                            <td>{{ number_format($classe->scolarite, 0, ',', '.') }} CFA</td>
                                            <td>
                                                <a class="btn-sm btn-warning m-3" href="#" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}">
                                                    <i class="fas fa-edit"></i> Editer
                                                </a>

                                                <form action="{{ route('classe.delete', ['classe' => $classe->id]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')">
                                                        <i class="fas fa-trash-alt"></i> Supprimer
                                                    </button>
                                                </form>

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
                <h5 class="modal-title" id="exampleModalLabel">Enregistré une classe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{ route('classe.store') }}" method="post">
                    @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Classe</label>
                        <input type="text" name="classe" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Scolarité</label>
                        <input type="number" name="scolarite" min="0" class="form-control" required>
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
        @foreach ($classes as $classe)
            <div class="modal fade" id="editEntry{{ $loop->iteration }}">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editer la classe</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                    <form class="settings-form" action="{{ route('classe.update', ['classe'=>$classe->id]) }}" method="POST">
                        @csrf
                        @method('PUT')


                        <div class="form-group">
                            <label>Classe</label>
                            <input type="text" name="classe" class="form-control" value="{{ $classe->nom }}"  required>
                        </div>

                        <div class="form-group">
                            <label>Scolarité</label>
                            <input type="number" name="scolarite" min="0" value="{{ $classe->scolarite }}"  class="form-control" required>
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