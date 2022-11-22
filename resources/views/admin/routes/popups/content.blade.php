{!! Form::model($route, ['route' => ['admin.'. $entity . '.setUser', $route], 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page']) !!}
{!! Form::hidden('id', $route->id) !!}
<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>
<h2>{{ trans('admin.'. $entity . '.set_user') }}</h2>
<div class="hr-line-dashed"></div>
<div class="row">
<div class="col-md-12">
        @if($users->count())
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th class="td-actions">#</th>
                        <th>{{ trans('admin_labels.first_name') }}</th>
                        <th>{{ trans('admin_labels.phone') }}</th>
                        <th>{{ trans('admin_labels.role') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <div class="js_checkbox-wrap">
                    <tr>
                        <th colspan="4">
                            <div class="checkbox mt-5">
                        {{ Form::checkbox(null, null, $users->count() == $checked->count(), ['class' => 'js_checkbox-all', 'id' => 'users[all]']) }}
                        {{ Form::label('users[all]', 'Выбрать всех', ['class' => 'text-weight text-warning']) }}
                    </div>
                        </th>
                    </tr>
                    
                    
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="checkbox m-n p-t-n">
                                    {{ Form::checkbox('users['. $user->id .']', $user->id, $checked->search($user->id), ['class' => 'js_checkbox', 'id' => 'users['. $user->id .']']) }}
                                    {{ Form::label('users['. $user->id .']', $user->id) }}
                                </div>
                            </td>
                            <td>
                                <span data-toggle="tooltip"
                                    title="@phone($user->phone)">{{ $user->first_name }}</span>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-default text-success" style="border:0px;"
                                            data-toggle="dropdown">
                                        <i class="text-success fa fa-phone"></i> {{ $user->phone }}
                                    </button>
                                </div>
                            </td>
                            <td>
                                <span data-toggle="tooltip">{{ $user->roles->first()->name ?? '---'}}</span>
                            </td>
                        </tr>
                    @endforeach
                    </div>
                    </tbody>
                </table>
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
{{ Form::panelButton(trans('admin.buses.rent.send')) }}
{!! Form::close() !!}

