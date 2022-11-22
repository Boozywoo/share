<div class="filter_flex">
    <div><a href="{{ route('admin.operational_tasks.index', ['status'=>'new']) }}" class="btn custom__style {{ $status == 'new' ? 'custom__style-active' : ''}}"><span class="fa fa-exclamation-triangle"></span>   Новые</a></div>
    <div><a href="{{ route('admin.operational_tasks.index', ['status'=>'work']) }}" class="btn custom__style {{ $status == 'work' ? 'custom__style-active' : ''}}"><span class="fa fa-cog"></span>   В работе</a></div>
    <div><a href="{{ route('admin.operational_tasks.index', ['status'=>'completed']) }}" class="btn custom__style {{ $status == 'completed' ? 'custom__style-active' : ''}}"><span class="fa fa-check-circle"></span>   Решенные</a></div>
    <div><a href="{{ route('admin.operational_tasks.index', ['status'=>'closed']) }}" class="btn custom__style {{ $status == 'closed' ? 'custom__style-active' : ''}}"><span class="fa fa-times-circle"></span>   Закрытые</a></div>

    <div><a href="{{ route('admin.operational_tasks.create') }}" class="btn btn-warning" style="padding: 6px 40px;">Создать задачу</a></div>
</div>
