@extends('layout.master')

@section('content')


    <div class="main-content">
        <section class="section">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('accueil.index') }}"><i class="fas fa-tachometer-alt"></i> Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i> Mon Profil</li>
                </ol>
            </nav>

          <div class="section-body">
            <div class="row mt-sm-4">
              <div class="col-12 col-md-12 col-lg-4">
                <div class="card author-box">
                    <div class="card-body">
                        <div class="author-box-center">
                            <img alt="image" src="{{ asset('assets/img/users/user.jpg') }}" class="rounded-circle author-box-picture">
                            <div class="clearfix"></div>
                            <div class="author-box-name">
                                <a href="#">{{ $user->name }}</a>
                            </div>
                            <div class="author-box-job">{{ $user->role->role }}</div>
                        </div>
                        <div class="text-center">
                            <div class="author-box-description">
                                <p>
                                    @if($user->role->role == 'ADMIN')
                                        🌟 Vous êtes le pilier de cette plateforme ! Votre leadership façonne l’avenir et inspire votre équipe à donner le meilleur d’elle-même. Continuez à innover et à guider vers l’excellence !
                                    @elseif($user->role->role == 'COMPTABLE')
                                        💰 Votre rigueur et votre expertise assurent une gestion financière impeccable ! Grâce à vous, l’école avance avec confiance et sérénité. Continuez à faire la différence, chaque chiffre compte !
                                    @else
                                        🚀 Bienvenue ! Votre rôle est essentiel dans la réussite collective. Ensemble, bâtissons un avenir solide et plein de succès !
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                  <div class="card-header">
                    <h4>Details Personnels</h4>
                  </div>
                  <div class="card-body">
                    <div class="py-4">
                      <p class="clearfix">
                        <span class="float-left">
                          Date de création
                        </span>
                        <span class="float-right text-muted">
                          {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Téléphone
                        </span>
                        <span class="float-right text-muted">
                          {{ $user->telephone }}
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          E-Mail
                        </span>
                        <span class="float-right text-muted">
                          {{ $user->email }}
                        </span>
                      </p>
                      
                    </div>
                  </div>
                </div>
               
              </div>
              <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                  <div class="padding-20">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                        <li class="nav-item">
                        <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings" role="tab"
                          aria-selected="false">Changer Mon mot de passe</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                          aria-selected="true">A propos</a>
                      </li>
                      
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade " id="about" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-md-3 col-6 b-r">
                            <strong>Full Name</strong>
                            <br>
                            <p class="text-muted">Emily Smith</p>
                          </div>
                          <div class="col-md-3 col-6 b-r">
                            <strong>Mobile</strong>
                            <br>
                            <p class="text-muted">(123) 456 7890</p>
                          </div>
                          <div class="col-md-3 col-6 b-r">
                            <strong>Email</strong>
                            <br>
                            <p class="text-muted">johndeo@example.com</p>
                          </div>
                          <div class="col-md-3 col-6">
                            <strong>Location</strong>
                            <br>
                            <p class="text-muted">India</p>
                          </div>
                        </div>
                        
                      </div>
                      <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                        <form action="{{ route('password.update') }}" method="post">
                            @csrf
                          <div class="card-header">
                            <h4>Editer mon mot de passe</h4>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="form-group col-md-12 col-12">
                                <label>Ancien mot de passe</label>
                                <input name="old_password" id="old_password" type="password" value="{{old('old_password')}}" class="form-control" required>
                                @error('old_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 col-12">
                                <label>Nouveau mot de passe</label>
                                 <input name="password" id="password" type="password" class="form-control" value="{{old('password')}}" required>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                              </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-md-12 col-12">
                            <label>Confirmer mot de passe</label>
                            <input name="password_confirmation" id="password_confirmation" value="{{old('password_confirmation')}}" type="password" class="form-control" required>
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            </div>
                              
                            </div>
                            
                          </div>
                          <div class="card-footer text-right">
                            <button class="btn btn-primary">Valider</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        
    </div>


@endsection