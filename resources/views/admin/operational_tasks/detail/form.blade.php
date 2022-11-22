<style>
  .ibox-buttonStatus{
    margin-bottom: 20px;
  }
  .fa-times-circle{
    margin-right: 2px;
  }
</style>

<div class="ibox-content-custom">

    <div class="row">
        <h4>Задача</h4>
        <div class="ibox-buttonStatus">
            <div>
                <a href="javascript:void(0)" data-status="new" class="changeStatus btn custom__style {{ $task->status == 'new' ? 'custom__style-active' : ''}}">
                    <span class="fa fa-exclamation-triangle"></span>Новая
                </a>
            </div>
            <div><a href="javascript:void(0)" data-status="in-progress"
                    class="changeStatus btn custom__style {{ $task->status == 'in-progress' ? 'custom__style-active' : ''}}">
                    <span class="fa fa-cog"></span> В работе
                </a>
            </div>
            <div><a href="javascript:void(0)" data-status="closed"
                    class="btn changeStatus custom__style {{ $task->status == 'closed' ? 'custom__style-active' : ''}}">
                    <span class="fa fa-times-circle"></span>Закрыто
                </a>
            </div>
            <div><a href="javascript:void(0)" data-status="completed"
                    class="btn changeStatus custom__style {{ $task->status == 'completed' ? 'custom__style-active' : ''}}"><span
                            class="fa fa-check-circle"></span> Решено</a>
            </div>
        </div>
        <div class="col-md-12">
            {{ Form::input('hidden', 'status', $task->status) }}
            {{ Form::panelSelect('responsible', $responsibles, $task->responsible->id,  ['class'=>'js-user-type-single form-control']) }}
            {{ Form::panelText('subject', $task->subject, null) }}

            {{ Form::panelTextarea('description',false, null, null, [], $task->description) }}
            @if($task->files)
                <div style="display: flex; justify-content: flex-end">
                    <div class="form-group">
                        @foreach($task->files as $file)
                            @if($file->type == 'image')
                                <a href="{{ asset('storage/'. $file->src) }}" target="_blank">
                                    <img src="{{ asset('storage/'. $file->src) }}" alt="" width="200px">
                                </a>
                            @else
                                <i class="fa fa-file"></i>
                                <a href="{{ asset('storage/'. $file->src) }}">{{ $file->original_name }}</a><span></span>
                            @endif
                            <br>
                            <br>
                        @endforeach
                    </div>
                </div>
            @endif
            <hr>
            @if($task->history)
                @foreach($task->history as $history)
                    @if(isset($history->comment))
                        <h4 style="text-align: end;">{{ $history->created_at->format('d.m.Y H:i') }}</h4>
                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ $history->user->full_name }}</label>
                            <div class="col-md-8">
                                <textarea readonly class="form-control " cols="50" rows="2" id="comment">{{$history->comment}}</textarea>
                            </div>
                        </div>
                        @if($history->files)
                            <div style="display: flex; justify-content: flex-end">
                                <div class="form-group">
                                    @foreach($history->files as $file)
                                        @if($file->type == 'image')
                                            <a href="{{ asset('storage/'. $file->src) }}" target="_blank">
                                                <img src="{{ asset('storage/'. $file->src) }}" alt="" width="200px">
                                            </a>
                                        @else
                                            <i class="fa fa-file"></i>
                                            <a href="{{ asset('storage/'. $file->src) }}">{{ $file->original_name }}</a><span></span>
                                        @endif
                                        <br>
                                        <br>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <hr>
                    @endif
                    @if(isset($history->status))
                        <h4 style="text-align: end">{{ $history->created_at->format('d.m.Y H:i') }}</h4>
                        <h4 style="text-align: end; color: #1ab394">{{ $history->user->full_name }} сменил статус на {{$history->status}}</h4>
                        <hr>
                    @endif
                @endforeach
            @endif

            <div class="form-group">
                {!! Form::label('comment',trans('admin_labels.comment'),
                    ['class' => 'col-md-4 control-label']
                ) !!}
                <div class="col-md-8">
                    {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
                    <p class="error-block"></p>
                </div>
            </div>
            <div class="form-group">
                <label for="comment-files[]" class="col-md-4 control-label">Файлы</label>
                <div class="col-md-8">
                    {!! Form::file('comment-files[]', ['multiple' =>'multiple', 'id' => 'upload-file']) !!}
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    </div>
</div>

<script>
  $(function() {
    $('.changeStatus').on('click', function() {
      var status = $(this).data('status');
      $('[name="status"]').val(status);
      $('.changeStatus').removeClass('custom__style-active')
      $(this).addClass('custom__style-active')
    });
  });

  $(document).on('DOMContentLoaded', function() {
    $('#upload-file').on('change', function() {
      $('#files-container .form-group').empty();
      let files = this.files;
      let filesLength = files.length;
      if (filesLength > 0) {
        let parent = $(this).parent().closest('.form-group');
        for (let i = 0; i < filesLength; i++) {
          let filesContainer = $('#files-container .form-group');
          let file = files[i];
          let src = window.URL.createObjectURL(file);
          let append = '<a href="' + src + '" target="_blank"><img src="' + src + '" alt="" width="200px"></a>';
          if (file.type.indexOf('image') === -1) {
            append = '<i class="fa fa-file"></i><a href="' + src + '">' + file.name + '</a><span></span>'
          }
          if (filesContainer.length > 0) {
            filesContainer.append(append);
          } else {
            let filesContainerHtml = '<div id="files-container">' + '<div class="form-group">' + append + '</div>' + '</div>'
            parent.prepend(filesContainerHtml);
          }
        }
      }
    })
  })

</script>
