<b>{{ trans('admin_labels.busy_places') }}</b> {{ $tour->busyPlacesCount }}<br>
<b>{{ trans('admin_labels.in_pull') }}</b> {{ $tour->ordersPull->count()  }}<br>
@if(isset($noCoincided))
    <b class="text-danger">{{ trans('admin_labels.no_coincided_places') }}</b> {{ $noCoincided  }}<br>
@endif