<div class="dropdown profile-element">
    <span>
        {{--<img alt="image" class="img-circle" src="assets/img/profile_small.jpg" />--}}
    </span>
    {{--<a class="dropdown-toggle" href="{{ route('admin.users.show', auth()->user()) }}">--}}
    @if(Auth::user())
    <a class="dropdown-toggle" href="">
        <span class="clear">
            <span class="block m-t-xs"><strong class="font-bold"><h2>{{ auth()->user()->first_name }}</h2></strong></span>
            <span class="text-muted text-xs block">{{ auth()->user()->email }} </span>
        </span>
    </a>
    @endif
</div>