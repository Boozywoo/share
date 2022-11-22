@extends('index.layouts.main')

@section('title', $page->generateMetaTitle)
@section('meta_description', $page->generateMetaDescription)

@section('content')
    <div class="item-page about mainWidth backg" style="flex: 500">
        <h1>{{ $page->title }}</h1>
        <div class="content">
            {!! $page->content !!}
        </div>
    </div>
@endsection