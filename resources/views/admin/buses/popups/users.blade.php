{!! Form::model($bus, ['route' => ['admin.'.$entity.'.set-bus-users', $bus], 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page']) !!}
{!! Form::hidden('id', $bus->id) !!}
<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>
<h2>{{ trans('admin.'. $entity . '.set_user') }}</h2>
<div class="hr-line-dashed"></div>
<div class="row">
    <div class="col-md-12">
        @if($users->count() || $drivers->count())
            <div class="table-responsive">
                <div class="js_checkbox-wrap">

                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th class="td-actions">#</th>
                            <th>{{ trans('admin_labels.bus_id') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th colspan="4">
                                <div class="checkbox mt-5">
                                    {{ Form::checkbox(null, null, $users->count() + $drivers->count() == $busUsers->count() + $busDrivers->count(),
                                        ['class' => 'js_checkbox-all', 'id' => 'users[all]']) }}
                                    {{ Form::label('users[all]', 'Выбрать всех', ['class' => 'text-weight text-warning']) }}
                                </div>
                            </th>
                        </tr>


                        @foreach($users as $user)

                            <tr>
                                <td>
                                    <div class="checkbox m-n p-t-n">
                                        {{ Form::checkbox('users[]', $user->id,
$busUsers->contains($user->id),
['class' => 'js_checkbox', 'id' => 'user_'. $user->id]) }}
                                        {{ Form::label('user_'.$user->id, ' ') }}
                                    </div>
                                </td>
                                <td>
                                    <span data-toggle="tooltip">{{ $user->name }}</span>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($drivers as $driver)

                            <tr>
                                <td>
                                    <div class="checkbox m-n p-t-n">
                                        {{ Form::checkbox('drivers[]', $driver->id, $busDrivers->contains($driver->id), ['class' => 'js_checkbox', 'id' => 'driver_'. $driver->id]) }}
                                        {{ Form::label('user_'. $driver->id, ' ') }}
                                    </div>
                                </td>
                                <td>
                                    <span data-toggle="tooltip">{{ $driver->name }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        @else
            <p class="text-muted m-t-sm">Пользователи не найдены</p>
        @endif
    </div>
</div>
<div class="hr-line-dashed"></div>
<script>

    $(document).on('change', '.js_checkbox-all', checkboxAll);
    $(document).on('change', '.js_checkbox', checkbox);

    function checkboxAll() {
        $('.js_checkbox').prop('checked', $(this).is(':checked')).change();
    }

    function checkbox() {
        var $table = $(this).closest('.js_checkbox-wrap');
        var $checked = $table.find('.js_checkbox:checked');
        var $all = $table.find('.js_checkbox');
        if($checked.length == $all.length) {
            $table.find('.js_checkbox-all').prop('checked', true);
        } else {
            $table.find('.js_checkbox-all').prop('checked', false);
        }
    }    
</script>
{{ Form::panelButton() }}
{!! Form::close() !!}

