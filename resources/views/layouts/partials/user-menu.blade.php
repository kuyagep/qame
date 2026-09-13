<li class="nav-item dropdown">

    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user-circle"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        <a href="{{ route('profile') }}" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profile
        </a>

        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item text-danger" id="logoutBtn">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>

    </div>

</li>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>
