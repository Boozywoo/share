<div class="table-responsive">
    <style>
        .btn-show {
            display: inline-block;
            vertical-align: middle;
        }
        #card-open{
            position: absolute;
            right: 10px;
            top: 3px;
        }
    </style>
    <script>
        function showCardTable() {
            $("#card-line #caret-down").toggle();
            $("#card-line #caret-up").toggle();
            $("#card-table").toggle();
        }

    </script>
    <div style="position: relative; cursor: pointer; text-align: center" id="card-line">
        <h3 onclick="showCardTable()" style="display: inline-block">{{__('admin.repair_orders.fields.repair_map')}}</h3>
        <div onclick="showCardTable()" class="btn-show" id="caret-down">
            <span class="fa fa-caret-down" style="font-size: 22px;color: #f8ac59;"></span>
        </div>
        <div onclick="showCardTable()" class="btn-show" id="caret-up" style="display: none">
            <span class="fa fa-caret-up" style="font-size: 22px; color: #f8ac59;"></span>
        </div>
        <a href="{{route('admin.repair_orders.diagnostic_cards.edit',[$repairOrder,$repairOrder->diagnostic_card->id])}}"
           class="btn-show" id="card-open">
            <span class="fa fa-eye" style="font-size: 20px; color: #f8ac59;"></span>
        </a>
    </div>

    <table class="table table-condensed" id="card-table" style="display: none">
        <thead>
        <tr>
            <th>{{ trans('admin_labels.category_id') }}</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.comment') }}</th>
            <th>{{ trans('admin_labels.photo') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cardItems as $cardItem)
            @if($cardItem->item && $cardItem->item->parent)

                <tr>
                    <td>{{ $cardItem->item->parent->name }}</td>
                    <td>{{ $cardItem->item->name}}</td>
                    <td>{{$cardItem->comment}}</td>
                    <td>
                        @foreach($cardItem->images as $image)
                            <a href="{{ $image->load() }}" data-gallery="" class="js_panel_images-zoom ">
                                <img src="{{$image->load()}}" alt=""
                                     style="display: inline-block; width: 60px; height: 60px; object-fit: contain">
                            </a>
                        @endforeach

                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>

