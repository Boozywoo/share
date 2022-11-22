<nav class="navbar-default navbar-static-side" role="navigation">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                @include('panel::layouts.partials.sidebar.user')
                <div class="logo-element">
                    NAV
                </div>
            </li>
            @include('admin.partials.menu')
        </ul>
    </div>
</nav>