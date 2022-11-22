@if($routes->count())
    <div class="js-sortable-station js_multiple-wrapper">
        <div class="row">
            <div class="col-md-3">
                #
            </div>
            <div class="col-md-3">
                {{ trans('admin_labels.status') }}
            </div>
            <div class="col-md-3">
                {{ trans('admin_labels.name') }}
            </div>
            <div class="col-md-3">
                {{ trans('admin_labels.interval') }}
            </div>
        </div>
    </div>
    <form method="GET">
        <div class="js-sortable-station js_multiple-wrappe">
            @php($i = 0)
            @foreach($routes as $key => $route)
                <div class="row js_multiple-row js_reindex-stations" data-name="stations">
                    <input type="hidden" name="stations[{{$i++}}]" value="{{$route->id}}">
                    <div class="row">
                        <div class="col-md-3">
                            <span class="input-group-btn"><span class="btn btn-default js_multiple-order">{{ $route->id }}</span></span>
                        </div>
                        <div class="col-md-3">
                            {!! trans('pretty.statuses.'. $route->status ) !!}
                        </div>
                        <div class="col-md-3">
                            <a href="{{route ('admin.'. $entity . '.edit', $route)}}" class="pjax-link">{{ $route->name }}</a>
                        </div>
                        <div class="col-md-3">
                            {{ $route->getIntervalActive() }} {{trans('admin.routes.min')}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <input type="submit" value="{{trans('admin.buses.rent.send')}}">
    </form>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif