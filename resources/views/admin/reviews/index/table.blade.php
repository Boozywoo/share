@if($reviews->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.company_id') }}</th>
            <th>{{ trans('admin_labels.rating') }}</th>
            <th>{{ trans('admin_labels.comment') }}</th>
            <th>{{ trans('admin_labels.created_at') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($reviews as $review)
            @if(isset($review->client->first_name))
            <tr>
                <td>{{ $review->id }}</td>
                <td>
                    {{ $review->order->tour->bus->company->name }} <br>
                    <span class="label">Маршрут</span> <br>
                    {{ $review->order->tour->route->name }} <br>
                    {{ $review->order->tour->prettyTime }}
                </td>
                <td>
                    <span class="{{ $review->type == \App\Models\Review::TYPE_POSITIVE ? 'text-warning' : 'text-danger' }}">{!! $review->prettyRating !!}</span> <br>
                    <span class="label">Клиент</span> <br>
                    <a href="{{ route('admin.clients.edit', $review->client) }}" class="pjax-link">{{ $review->client->first_name }}</a>
                </td>
                <td>
                    {{ $review->comment }} <br>
                    <span class="label">Водитель</span> <br>
                    <a href="{{ route('admin.drivers.edit', $review->order->tour->driver) }}" class="pjax-link">{{ $review->order->tour->driver->full_name }}</a>
                </td>
                <td>
                    @date($review->created_at)
                </td>
                <td class="td-actions">
                </td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif