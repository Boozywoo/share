@extends('index.layouts.main')

@section('title', trans('index.profile.personal_account'))

@section('content')

    <div class="item-page personalCabinet reviews mainWidth">
        <ul class="breadCrumbs">
            <li><a href="{{ route('index.home') }}">{{ trans('admin.home.title') }}</a></li>
            <li><a href="{{ route('index.profile.settings.index') }}">{{ trans('index.profile.personal_account')}}</a></li>
            <li><a class="thisPage">{{ trans('index.profile.feedbacks')}}</a></li>
        </ul>
        <div class="mainWidth">
            <p class="title">{{ trans('index.profile.feedbacks')}}</p>
            <div class="left">
                @include('index.profile.partials.menu')
            </div>
            <div class="right">
                @if($reviews->count())
                <table class="reviewsTable" style="width: 100%;">
                    <colgroup>
                        <col>
                        <col>
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>{{ trans('index.profile.my_route')}}</th>
                        <th>{{ trans('index.profile.date_and_time')}}</th>
                        <th>{{ trans('index.profile.feedback')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>{{ $review->order->tour->route->name }}</td>
                        <td>
                            {{ $review->order->tour->prettyTime }}
                        </td>
                        <td>
                            <ul class="gradeStars">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <li class="golden"></li>
                                @endfor
                                @for($i = 0; $i < 5 - $review->rating; $i++)
                                    <li></li>
                                @endfor
                            </ul>
                            {{ $review->comment }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                    <p>{{ trans('index.profile.not_left')}}</p>
                @endif
            </div>
            <br style="clear: both"/>
        </div>
    </div>
@endsection