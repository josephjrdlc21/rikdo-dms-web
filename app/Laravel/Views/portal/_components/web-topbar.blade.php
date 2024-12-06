<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <a href="{{route('portal.home')}}" class="navbar-brand sidebar-gone-hide">
        <img src="{{asset('assets/img/rikdo-logo.png')}}" alt="logo" width="45" class="img-fluid rounded-circle">
        RIKDO DMS
    </a>
    <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
    <div class="nav-collapse">
        <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
            <i class="fas fa-ellipsis-v"></i>
        </a>
        <ul class="navbar-nav">
            <li class="nav-item {{request()->segment(1) == "home" ? "active" : ""}}"><a href="{{route('portal.home')}}" class="nav-link">Home</a></li>
            <li class="nav-item {{request()->segment(1) == "about" ? "active" : ""}}"><a href="{{route('portal.about')}}" class="nav-link">About</a></li>
            <li class="nav-item {{request()->segment(1) == "contact" ? "active" : ""}}"><a href="{{route('portal.contact')}}" class="nav-link">Contact</a></li>
        </ul>
    </div>
    <form class="form-inline ml-auto">
        <ul class="navbar-nav">
            <li></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="nav-item"><a href="{{route('portal.auth.login')}}" class="nav-link">Login</a></li>
        <li class="nav-item"><a href="{{route('portal.auth.register')}}" class="nav-link">Register</a></li>
    </ul>
</nav>
<nav class="navbar navbar-secondary navbar-expand-lg">
    <div class="container">
        <ul class="navbar-nav">
            <li class="nav-item {{request()->segment(1) == "researches" ? "active" : ""}}">
                <a href="{{route('portal.researches')}}" class="nav-link"><i class="fas fa-file"></i><span>Researches</span></a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="fas fa-signal"></i><span>Statistics</span></a>
            </li>
        </ul>
    </div>
</nav>