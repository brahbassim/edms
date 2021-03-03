<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion  text-center" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('index-dashboard')}}">
        <div class="sidebar-brand-icon ">
            <img src="{{asset('/img/edms.jpg')}}" alt="" style="width: 70px;">
        </div>
        <div class="sidebar-brand-text mx-3">EDMS</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    @can('list-dashboard')
    <li class="nav-item {{ activeChild(['index-dashboard']) }}">
        <a class="nav-link" href="{{ route('index-dashboard') }}">
            <!--<i class="fas fa-fw fa-tachometer-alt"></i><br>-->
            <span>Tableau de bord</span>
        </a>
    </li>
    @endcan

    @can('index-folder')
    <hr class="sidebar-divider ">
      <li class="nav-item {{ activeChild(['index-folder','search-document','index-document']) }}">
        <a class="nav-link" href="{{ route('index-folder') }}">
            <i class="fas fa-file"></i>
            <span>Décrets</span>
        </a>
    </li>  
    @endcan

    @can('index-stat')
    <hr class="sidebar-divider ">
      <li class="nav-item {{ activeChild(['index-stat']) }}">
        <a class="nav-link" href="{{ route('index-stat') }}">
            <i class="fas fa-project-diagram"></i>
            <span>Statistiques</span>
        </a>
    </li>  
    @endcan
    
    @can('index-decoration')
    <hr class="sidebar-divider">
       <li class="nav-item {{ activeChild(['index-decoration','search-decoration','index-decoration']) }}">
        <a class="nav-link" href="{{ route('index-decoration') }}">
            <i class="fas fa-flag"></i>
            <span>Décorations</span>
        </a>
    </li> 
    @endcan

    @can('index-grade')
    <hr class="sidebar-divider">
        <li class="nav-item {{ activeChild(['index-grade']) }}">
        <a class="nav-link" href="{{ route('index-grade') }}">
            <i class="fas fa-list"></i>
            <span>Grades</span>
        </a>
    </li>    
    @endcan

    @can('edit-profile')
    <hr class="sidebar-divider">
        <li class="nav-item {{ activeChild(['edit-profile']) }}">
            <a class="nav-link" href="{{ route('edit-profile') }}">
                <i class="fas fa-user"></i>
                <span>Compte</span>
            </a>
        </li>
    @endcan

    @can('index-user')
     <hr class="sidebar-divider">
        <li class="nav-item {{ activeChild(['index-user','index-prof']) }}">
            <a class="nav-link" href="{{ route('index-user') }}">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>
        </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline mt-4">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
