<div class="moduletable">
    <ul id="personalCabinetMenu" class="nav menu">
        <li class="personalCabinetSMM">
            <a href="{{ route('index.profile.settings.index') }}" class="{{ request()->url() == route('index.profile.settings.index') ? 'menu_profile__active' : '' }}">{{trans('index.profile.personal')}}</a>
        </li>
        <li class="myTickets">
            <a href="{{ route('index.profile.tickets.index', 'upcoming') }}" class="{{ request()->url() == route('index.profile.tickets.index') ? 'menu_profile__active' : '' }}">{{ trans('index.profile.my_tickets')}}</a>
        </li>
        {{--<li class="rewardPoints">--}}
            {{--<a href="reward_points.php">{{ trans('index.profile.bonus_points')}}</a>--}}
        {{--</li>--}}
        {{--<li class="minibusLocation">--}}
        {{--<a href="minibus_location.php">{{ trans('index.profile.where_is_minibus')}}</a>--}}
        {{--</li>--}}
        <li class="reviews">
            <a href="{{ route('index.profile.reviews', 'done') }}" class="{{ request()->url() == route('index.profile.reviews') ? 'menu_profile__active' : '' }}">{{ trans('index.profile.feedbacks')}}</a>
        </li>
        <li>
            <a href="{{ route('index.auth.logout') }}">{{ trans('admin.filter.exit') }}</a>
        </li>
    </ul>
</div>