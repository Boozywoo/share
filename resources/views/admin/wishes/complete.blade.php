<style>
    .ibox-content-custom {
        display: flex;
        justify-content: center;
    }

    .ibox-content-custom > .row {
        background-color: rgba(77, 77, 77, 0.9);;
        padding: 20px;
        border-radius: 20px;
    }

    .ibox-content-custom {
        display: flex;
        justify-content: center;
    }

    .ibox-content-custom > .row {
        min-width: auto;
        margin: 25px;
        background-color: rgba(77, 77, 77, 0.9);;
        padding: 20px 50px 20px 50px;
        border-radius: 20px;
    }

</style>
@extends('panel::layouts.main')
@section('title', trans('admin.'. $entity . '.title') . ' / ' . trans('admin.'. $entity . '.delegate'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{ trans('admin.filter.back') }}</a>
@endsection
@section('main')
    <div class="ibox">
        <div class="ibox-content">
            @include('admin.'. $entity . '.index.filter')
            <div class="hr-line-dashed"></div>
        </div>
    </div>
    {!! Form::model($wishes,['route' => ['admin.'. $entity . '.completeStore', $wishes], 'class' => "form-horizontal js_form-ajax js_form-ajax-redirect"])  !!}
            {!! Form::hidden('id') !!}
            <div class="ibox-content-custom">
                <div class="row">
                    <h4 style="text-align: center">Ззавершение заявки</h4>
                    <br>
                    <hr>
                        {{ Form::panelTextarea('comment_complete','','', '', []) }}
                    <div class="ibox-footer">
                        {{ Form::panelButton('Завершить') }}
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
        </div>
    </div>
    {!! Form::close() !!}

@endsection
