
<style>
    th, td {
        text-align: center;
        min-width: 125px;
    }

    th.w-one {
        min-width: 75px;
    }

    th.w-two {
        min-width: 125px;
    }

    th.w-three {
        min-width: 175px;
    }

    td > .field-input {
        display: none;
    }

    .field-input > div, span {
        display: inline-block;
        cursor: pointer;
    }

    span > .fa-close {
        color: #ed5565;
    }

    span > .fa-check {
        color: green;
    }
</style>
<div class="table-responsive">

    <table class="table table-condensed table-hover" id="dashboard">

        <thead>
        <tr>
            <th class="w-one">#</th>
            @foreach($selectedFields as $field)
                <th>
                    <div data-toggle="modal" data-target="#db_bus-filter"
                         onclick="chooseField('{{$field}}')"
                         style="cursor: pointer; {{request()->has('filter') && !empty(request('filter')[$field]) ? "color:  yellow;" : ''}}">{{__("admin_labels.$field")}}</div>
                </th>
            @endforeach
        </tr>
        </thead>

        <tbody>

        @if($buses->count())
            @foreach($buses as $modelBus)
                @php
                    $bus = collect($modelBus);
                @endphp

                <tr id="row_{{$bus->get('id')}}">
                    @include('admin.dashboards.buses.index.row', [$bus,$departments,$modelBus])
                </tr>
            @endforeach
        @endif

        </tbody>

    </table>
    @if(!$buses->count())
        <tr>
            <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
        </tr>
    @endif

</div>
<script>
    var activeField = "";

    function showField(el, field, id) {
        console.log(el, field);
        activeField = field;
        $("#dashboard").find(".field-input").hide();
        $("#dashboard").find(".field-value").show();
        $(el).find(".field-value").hide();
        $(el).find(".field-input").show();
    }

    function hideField(id, field) {
        console.log(id, field);
        let el = $("#field_" + id + "_" + field);
        console.log(el.find(".field-value"));
        el.find(".field-value").show();
        el.find(".field-input").hide();
        activeField = "";
        // $("#field_" + id + "_" + field).trigger('dblclick');
    }

    function saveField(e, id, field) {
        let el = $("#row_" + id);
        let value = el.find("#field_" + id + "_" + field + " .field-input input").val() ?? null;
        if (!value) {
            value = el.find("#field_" + id + "_" + field + " .field-input select").val() ?? [];
        }
        console.log(el, value);
        let data = {};
        data[field] = value;
        data['field'] = field;
        console.log(data);
        $.ajax({
            type: 'POST',
            url: '/admin/dashboards/buses/' + id + '/update-one',
            data: data,
            success: function (data) {
                hideField(id, field);
                el.html(data.view);
                el.find(".field-value").show();
                el.find(".field-input").hide();

            }
        });

    }
</script>

