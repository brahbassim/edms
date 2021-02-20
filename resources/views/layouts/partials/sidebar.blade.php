<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion  text-center" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('index-dashboard')}}">
        <div class="sidebar-brand-icon ">
            <img src="{{asset('/img/edms.jpg')}}" alt="" style="width: 70px;">
        </div>
        <div class="sidebar-brand-text mx-3">OSAAS</div>
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

    <!-- Divider -->
    <hr class="sidebar-divider ">

    
    <li class="nav-item {{ activeChild(['index-folder','search-document','index-document']) }}">
        <a class="nav-link" href="{{ route('index-folder') }}">
            <i class="fas fa-folder"></i>
            <span>Dossiers</span>
        </a>
    </li>


    <hr class="sidebar-divider">

    @can('edit-profile')
        <li class="nav-item {{ activeChild(['edit-profile']) }}">
            <a class="nav-link" href="{{ route('edit-profile') }}">
                <i class="fas fa-user"></i>
                <span>Compte</span>
            </a>
        </li>
    @endcan

    @can('index-user')
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
