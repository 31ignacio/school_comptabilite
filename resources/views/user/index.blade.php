@extends('layout.master')

@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">

                 <div class="card-body">
                    <button type="button" class="btn  btn-outline-primary" data-toggle="modal" data-target="#basicModal"><i class="fas fa-plus"></i> Nouvel utilisateur</button>
                  </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                        <div class="card-header">
                            <h4>Liste des utilisateurs</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Téléphone</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user )
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @if($user->role->role == "ADMIN")
                <b class="badge badge-success">ADMIN</b>
            @else 
                <b class="badge badge-primary">COMPTABLE</b>
            @endif
        </td>
        <td>{{ $user->telephone }}</td>
        <td>
            <a class="btn-sm btn-warning m-1" href="#" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}">
                <i class="fas fa-edit"></i> Editer
            </a>

            <form action="{{ route('user.delete', ['user' => $user->id]) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm btn-danger m-1" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                    <i class="fas fa-trash-alt"></i> Supprimer
                </button>
            </form>

            <form action="{{ route('user.toggle', ['user' => $user->id]) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-sm btn-secondary m-1" onclick="return confirm('Êtes-vous sûr de vouloir {{ $user->estActif ? 'désactiver' : 'activer' }} cet utilisateur ?')">
                    <i class="fas fa-power-off"></i> {{ $user->estActif ? 'Désactiver' : 'Activer' }}
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
                <h5 class="modal-title" id="exampleModalLabel">Nouvel utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{ route('user.store') }}" method="post">
                    @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom & Prénoms</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="number" min="0" name="telephone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Rôle</label>
                        <select class="form-control" name="role" required>
                            <option value="">-- Choisir le rôle --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
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
        @foreach ($users as $user)
            <div class="modal fade" id="editEntry{{ $loop->iteration }}">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editer l'utilisateur</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                    <form class="settings-form" action="{{ route('user.update', ['user'=>$user->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Nom & Prénoms</label>
                            <input type="text" name="nom" class="form-control" value="{{ $user->name }}"  required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ $user->email }}"  class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="number" min="0" name="telephone" value="{{ $user->telephone }}" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Rôle</label>
                            <select name="role" id="role" class="form-control">
                                @if($user->role_id == 1)
                                    <option value="1" selected>ADMIN</option>
                                    <option value="2">COMPTABLE</option>
                                @else
                                    <option value="1">ADMIN</option>
                                    <option value="2" selected>COMPTABLE</option>
                                @endif
                            </select>
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