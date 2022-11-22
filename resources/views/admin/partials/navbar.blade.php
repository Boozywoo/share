<div class="row navbar">
    <nav class="navbar-static-top" role="navigation">
        <div class="navbar-header">
        <meta id="env_speed" name="env" content="{{ env('IS_CHECK_SPEED') }}">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-warning " href="#"><i class="fa fa-bars"></i> </a>
            {{--<div class="input-group-btn add-button-top">--}}
            {{--<button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle" type="button" aria-expanded="false"><span class="fa fa-plus"></span> Добавить <span class="caret"></span></button>--}}
            {{--<ul class="dropdown-menu pull-right">--}}
            {{--<li><a href="{{ route('admin.users.create') }}" class="pjax-link"><i class="fa fa-user"></i> Пользователя</a></li>--}}
            {{--</ul>--}}
            {{--</div>--}}
        </div>
        @if (Auth::user() && !Auth::user()->IsAgent && !Auth::user()->isMethodist)
            <ul class="nav navbar-top-links navbar-left">
                @permission('view.tours')
                <li class="{{ str_contains(request()->url(), route('admin.tours.index')) ? 'active1' : '' }}">
                    <a href="{{ route('admin.tours.index') }}" class="pjax-link">
                        <i class="fa fa-road"></i>
                        <span class="text">  {{ trans('admin.tours.title') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('view.clients')
                <li class="{{ str_contains(request()->url(), route('admin.clients.index')) ? 'active1' : '' }}">
                    <a href="{{ route('admin.clients.index') }}" class="pjax-link">
                        <i class="fa fa-users"></i>
                        <span class="text">  {{ trans('admin.clients.title') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('view.orders')
                <li class="{{ str_contains(request()->url(), route('admin.orders.index')) ? 'active1' : '' }}">
                    <a href="{{ route('admin.orders.index') }}" class="pjax-link">
                        <i class="fa fa-edit"></i>
                        <span class="text">  {{ trans('admin.orders.title') }}</span>
                    </a>
                </li>
                @endpermission
            </ul>
        @endif

        <ul class="nav navbar-top-links navbar-right">
            @if (Auth::user() && !Auth::user()->IsAgent && !Auth::user()->isMethodist)
                <li class="js_pull-count"></li>
            @endif
            <li>
                <a href="{{ route('admin.auth.logout') }}">
                    <i class="fa fa-sign-out"></i>{{ trans('admin.filter.exit') }}
                </a>
            </li>
            <li>
                @include('index.partials.elements.auth.language')
            </li>
        </ul>
        <div class="alert my-alert-warning alert-warning alert-dismissible" style="top: 5%; left: 40%; position: absolute; display: none; opacity: 0.6;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Облачный сервис Transport Manager не оплачен.</strong> 15 число крайний срок оплаты.
        </div>
        <div class="alert my-alert-danger btn-warning alert-dismissible" style="top: 5%; left: 40%; position: absolute; display: none; opacity: 0.6;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Облачный сервис Transport Manager будет выключен 20 числа.</strong>
        </div>
    </nav>
</div>
<script>
            @if (Auth::user())
                var user = '{{ Auth::user()->id}}';
            @endif
</script>