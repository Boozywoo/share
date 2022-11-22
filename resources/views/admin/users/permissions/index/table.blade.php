{!! Form::open(['route' => 'admin.'. $entity . '.store', 'class' => 'table-responsive js_form-ajax js_form-ajax-reset']) !!}
    <table class="table table-stripped table-bordered">
        <thead>
        <tr>
            <th>{{trans('admin.users.cr_vi_ed_name')}}</th>
            @foreach($roles as $role)
                <th class="text-center">{{ $role->name }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($permissions as $permission)
            <tr>
                <th>{{ $permission->name }}</th>
                @foreach($roles as $role)
                    <td class="text-center">
                        <div class="checkbox">
                            <input type="checkbox" name="permissions[{{ $role->id }}][]" value="{{ $permission->id  }}" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} id="permissions-{{ $role->id }}-{{ $permission->id }}"/>
                            <label for="permissions-{{ $role->id }}-{{ $permission->id }}"></label>
                        </div>
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="m-t-md">
        {{ Form::panelButton() }}
    </div>
{!! Form::close() !!}