<!DOCTYPE html>
<html lang="en">


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>SCHOOL | EDUCATION</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
  
  <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/bundles/izitoast/css/iziToast.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/bundles/select2/dist/css/select2.min.css') }}">
  <!-- Template CSS -->
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a>
            </li>
            
           
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown"
              class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i>
              <span class="badge headerBadge1">
                  {{ auth()->user()->unreadNotifications->count() }}
              </span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
                  Notifications
                  <div class="float-right">
                      <a href="{{ route('notifications.markAllRead') }}">Tout marquer comme lu</a>
                  </div>
              </div>

              <div class="dropdown-list-content dropdown-list-message">
                  @forelse(auth()->user()->unreadNotifications as $notification)
                      <a href="{{ $notification->data['url'] ?? '#' }}" class="dropdown-item">
                          <span class="dropdown-item-desc">
                              {!! $notification->data['message'] !!}
                              <div class="time text-primary">
                                  {{ $notification->created_at->diffForHumans() }}
                              </div>
                          </span>
                      </a>
                  @empty
                      <div class="dropdown-item">Aucune nouvelle notification</div>
                  @endforelse
              </div>
            </div>

          </li>

          
          
          <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
              class="nav-link notification-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
                Notifications
               
              </div>
             
            </div>
          </li>
          <li class="dropdown"><a href="#" data-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="{{ asset('assets/img/users/user.jpg') }}""
                class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title"> {{ auth()->user()->name }}</div>
              <a href="{{ route('accueil.profil') }}" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile
              </a> 
              <div class="dropdown-divider"></div>
              <a href="{{route('logout')}}" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Deconnexion
              </a>
            </div>
          </li>
        </ul>
      </nav>


      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html"> <img alt="image" src="assets/img/logo.png" class="header-logo" /> <span
                class="logo-name">Otika</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="dropdown">
              <a href="{{ route('accueil.index') }}" class="nav-link"><i data-feather="monitor"></i><span>Accueil</span></a>
            </li>
            @if(auth()->user()->role_id== 1)
              <li class="dropdown">
                <a href="{{ route('user.index') }}" class="nav-link"> <i data-feather="user"></i><span>Utilisateurs</span></a>
              </li>
              <li class="dropdown">
                <a href="{{ route('classe.index') }}" class="nav-link"> <i data-feather="layers"></i><span>Gestion des classes</span></a>
              </li>
              <li class="dropdown">
                <a href="{{ route('annee.index') }}" class="nav-link"><i data-feather="calendar"></i><span>Année scolaire</span></a>
              </li>
            @endif
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i>
                <span>Fiche élèves</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('eleve.index') }}">Enregistrer un nouveau élève</a></li>
                <li><a class="nav-link" href="{{ route('inscription.index') }}">Liste des élève par classe</a></li>
              </ul>
            </li>

             <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="user-plus"></i>
                <span>Fiche Enseignants</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('enseignant.index') }}">Liste des enseignants</a></li>
                {{-- <li><a class="nav-link" href="{{ route('inscription.index') }}">Liste des élève par classe</a></li> --}}
              </ul>
            </li>
            
          </ul>
        </aside>
      </div>
         @yield('content')
                
    </div>
  </div>

 
  <!-- General JS Scripts -->
  <script src="{{ asset('assets/js/app.min.js') }}"></script>

  
  <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
   <!-- Page Specific JS File -->
  <script src="{{ asset('assets/js/page/toastr.js') }}"></script>

  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <!-- Page Specific JS File -->
  <script src="{{ asset('assets/js/page/datatables.js') }}"></script>
  <script src="{{ asset('assets/bundles/izitoast/js/iziToast.min.js') }}"></script>

  <script src="{{ asset('assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>

  <!-- Custom JS File -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Page Specific JS File -->
  <script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>
  <!-- Page Specific JS File -->
  <script src="{{ asset('assets/js/page/sweetalert.js') }}"></script>

 <script>
    @if(session('success_message'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success_message') }}',
            showConfirmButton: false,
            timer: 7000
        });
    @endif

    @if(session('error_message'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ session('error_message') }}',
            showConfirmButton: false,
            timer: 7000
        });
    @endif
</script>
</body>
</html>