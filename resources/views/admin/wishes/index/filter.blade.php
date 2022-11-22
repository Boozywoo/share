@php
    $status = isset($wishes) ? $wishes->status : $status;
@endphp

<div class="filter_flex">
    <div><a href="{{ route('admin.wishes.index', ['status'=>'new']) }}" class="btn custom__style {{ $status == 'new' ? 'custom__style-active' : ''}}"><span class="fa fa-exclamation-triangle"></span>   Требуют внимания</a></div>
    <div><a href="{{ route('admin.wishes.index', ['status'=>'work']) }}" class="btn custom__style {{ $status == 'work' ? 'custom__style-active' : ''}}"><span class="fa fa-cog"></span>   Обращения в работе</a></div>
    <div><a href="{{ route('admin.wishes.index', ['status'=>'completed']) }}" class="btn custom__style {{ $status == 'completed' ? 'custom__style-active' : ''}}"><span class="fa fa-check-circle"></span>   Решенные обращения</a></div>

    <div><a href="{{ route('admin.wishes.create') }}" class="btn btn-warning" style="padding: 6px 40px;">Создать обращение</a></div>
</div>
