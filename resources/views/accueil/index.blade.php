@extends('layout.master')

@section('content')

<div class="main-content">
    <section class="section">
        <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Elève Inscrits</h5>
                          <h2 class="mb-3 font-18">{{ $nombreEleves }}</h2>
                          <p class="mb-0"><span class="col-green">{{ $annee->annee }}</span> Année</p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/1.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15"> Utilisateurs</h5>
                          <h2 class="mb-3 font-18">{{ $nombreUtilisateur }}</h2>
                          <p class="mb-0"><span class="col-orange">{{ $annee->annee }}</span> Année</p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/2.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Scolarité Validé</h5>
                          <h2 class="mb-3 font-18">{{ $nbSoldes }}</h2>
                          <p class="mb-0"><span class="col-green">{{ $annee->annee }}</span>
                            Année</p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/3.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Scolarité Non Validé</h5>
                          <h2 class="mb-3 font-18">{{ $nbNonSoldes}}</h2>
                          <p class="mb-0"><span class="col-red">{{ $annee->annee }}</span> Année</p>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/4.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </section>


      <section class="section">
        <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Liste des élèves n'ayant pas soldé leurs scolarités</h4>
                </div>
                <div class="card-body">

                <form action="{{ route('accueil.index') }}" method="GET">
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
                        <label for="filterAnnee">Année scolaire</label>
                      <select id="filterAnnee" class="form-control" name="annee_id" readonly>
                        <option value="{{ $annee->id }}" {{ $anneeId == $annee->id ? 'selected' : '' }}>
                            {{ $annee->annee }}
                        </option>
                      </select>
                    </div>
                      <div class="col-md-2 mt-4">
                        <button class="btn btn-outline-success" type="submit">
                            <i class="fas fa-search"></i> Filtre

                        </button>                                        
                    </div>
                  </div>
                </form>


                  <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                      <thead>
                        <tr>
                          <th>Maticule</th>
                          <th>Nom & Prénoms</th>
                          <th>Scolarité</th>
                          <th>Montant Payé</th>
                          <th>Reste A Payé</th>
                          <th>Statut</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($nonSoldes as $nonSolde )
                          <tr>
                          
                            <td>{{ $nonSolde['eleve']->matricule }}</td>
                            <td>{{ $nonSolde['eleve']->nom }} {{ $nonSolde['eleve']->prenom }}</td>
                            <td>{{ $nonSolde['scolariteTotale'] }}</td>
                            <td>{{ $nonSolde['montantPaye'] }}</td>
                             <td>{{ $nonSolde['resteAPayer'] }}</td>
                            {{-- <td class="align-middle">
                              <div class="progress progress-xs">
                                <div class="progress-bar bg-success width-per-40">
                                </div>
                              </div>
                            </td> --}}
                            
                            <td>
                              <div class="badge badge-danger badge-shadow">{{ $nonSolde['solde'] }}</div>
                            </td>
                            <td>
                              <a href="{{ route('inscription.show', ['inscription' => $nonSolde['id']]) }}" class="btn btn-sm btn-outline-primary rounded-pill m-2" title="Voir les détails"><i class="fas fa-eye"></i></a>
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








@endsection