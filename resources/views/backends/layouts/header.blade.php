<header class="c-header c-header-light c-header-fixed">
    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <i class="cil-menu c-icon c-icon-lg"></i>
    </button>
    <a class="c-header-brand d-lg-none c-header-brand-sm-up-center" href="#">
        <!-- <img src="{{ asset('assets/images/logo-rx-black.png') }}" alt="{{config('app.name')}}"> -->
        {{config('app.name')}}
    </a>
    <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
        <i class="cil-menu c-icon c-icon-lg"></i>
    </button>
    <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#"></a></li>
    </ul>
    <ul class="c-header-nav mfs-auto">
        <li class="c-header-nav-item px-3 c-d-legacy-none">
            <button class="c-class-toggler c-header-nav-btn d-none" type="button" id="header-tooltip" data-target="body" data-class="c-dark-theme" data-toggle="c-tooltip" data-placement="bottom" title="Toggle Light/Dark Mode">
                <i class="cil-moon c-icon c-d-dark-none"></i>
                <i class="cil-sun c-icon c-d-default-none"></i>
            </button>
        </li>
    </ul>
    <ul class="c-header-nav">
        <li class="c-header-nav-item dropdown">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">

                <div class="c-avatar"><i class="cil-settings"></i></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0">
                <div class="dropdown-header bg-light py-2"><strong>Settings</strong></div>
                <form action="{{route('logout')}}" method="post">
                    @csrf
                    <button class="dropdown-item" type="submit" href="#">
                        <i class="cil-account-logout c-icon mfe-2"></i>
                        Logout
                    </button>
                 </form>
            </div>
        </li>

    </ul>
</header>
