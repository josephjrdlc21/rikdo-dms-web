<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{$auth->user_info->directory && $auth->user_info->filename ? "{$auth->user_info->directory}/{$auth->user_info->filename}" : asset('assets/img/avatar/avatar-1.png')}}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{$auth->name}}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Logged in {{Carbon::parse($auth->last_login_at)->diffForHumans()}}</div>
                <a href="{{route('portal.profile.index')}}" class="dropdown-item has-icon">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="{{route('portal.profile.edit_password')}}" class="dropdown-item has-icon">
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{route('portal.auth.logout')}}" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>