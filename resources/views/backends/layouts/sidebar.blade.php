<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-md-down-none">
        <!-- <img src="{{ asset('assets/images/adidas_logo.svg') }}" width="50" alt="{{ config('app.name') }}"> -->
        <span>{{ config('app.name') }}</span>
    </div>
    <ul class="c-sidebar-nav">

        @hasanyrole('admin')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ Request::routeIs('backends.host') ? 'c-active' : '' }}" href="{{ route('backends.host.host') }}">
                <i class="cil-user c-sidebar-nav-icon"></i>
                รายชื่อห้อง
            </a>
        </li>
        @endrole

        @hasanyrole('super-admin')
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ Request::routeIs('backends.admin') ? 'c-active' : '' }}" href="{{ route('backends.admin.index') }}">
                    <i class="cil-user c-sidebar-nav-icon"></i>
                    ผู้ดูแลระบบ
                </a>
            </li>
        @endrole
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ Request::routeIs('backends.profile') ? 'c-active' : '' }}" href="{{ route('backends.profile.edit',['id' => Auth::User()->id]) }}">
                    <i class="cil-settings c-sidebar-nav-icon"></i>
                    ตั้งค่าบัญชี
                </a>
            </li>

            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="cil-account-logout c-sidebar-nav-icon"></i>
                    ออกจากระบบ
                </a>
            </li>
    </ul>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
