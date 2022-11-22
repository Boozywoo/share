    {!! Form::hidden('id', $wishes->id, ['id' => 'object_id']) !!}
    <div class="ibox-content-custom">

        <div class="row">
            <h4>Обращение {{ $wishes->id ? '#'. $wishes->id : '' }}</h4>
            @if($wishes->id)
                <div class="ibox-buttonStatus">
                    <div><a data-href="{{ route('admin.wishes.changeStatus', ['wishes'=>$wishes->id, 'status'=>'new']) }}" data-status="new" class="changeStatus btn custom__style {{ $wishes->status == 'new' ? 'custom__style-active' : ''}}"><span class="fa fa-exclamation-triangle"></span>    Новая</a></div>
                    <div><a data-href="{{ route('admin.wishes.changeStatus', ['wishes'=>$wishes->id,'status'=>'work']) }}" data-status="work" class="changeStatus btn custom__style {{ $wishes->status == 'work' ? 'custom__style-active' : ''}}"><span class="fa fa-cog"></span>     В работе</a></div>
                    @if($wishes->accessComplete())
                        <div><a href="{{ route('admin.wishes.complete', ['wishes'=>$wishes->id]) }}"  class="btn custom__style {{ $wishes->status == 'completed' ? 'custom__style-active' : ''}}"><span class="fa fa-check-circle"></span>     Решено</a></div>
                    @endif
                </div>
            @endif
            <h4>{{ $wishes->delegateName }} </h4>
            <div class="col-md-12">
                @php
                    $class = [];
                    if($readonly){
                          $class = ['disabled'=>'true'];
                    };
                @endphp
                {{ Form::panelSelect('wishes_type', $wishesTypes, $wishes->wishes_type_id,  ['class'=>'js-user-type-single form-control'] + $class) }}

                {{ Form::panelText('subject',$wishes->subject, null, [$readonly =>$readonly]) }}

                {{ Form::panelTextarea('comment',false, null, null,[$readonly =>$readonly] ) }}
                @if($wishes->id)
                    @if($wishes->files)
                        <div style="display: flex; justify-content: flex-end">
                            <div class="form-group">
                                @foreach($wishes->files as $file)
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
                    @foreach($history as $his)
                        @if($his->action == 'comment')
                            @php
                                $comment = $his->getInstanceData();
                            @endphp
                            <h4 style="text-align: end;">{{ $comment->created_at->format('d.m.Y H:i') }}</h4>
                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ $comment->user->fullname }}</label>
                                <div class="col-md-8">
                                    <textarea readonly class="form-control " cols="50" rows="2" id="comment">{{$comment->comment}}</textarea>
                                </div>
                            </div>
                            @if($comment->files)
                                <div style="display: flex; justify-content: flex-end">
                                    <div class="form-group">
                                        @foreach($comment->files as $file)
                                            @if($file->type == 'image')
                                                <a href="{{ asset('storage/'. $file->file) }}" target="_blank">
                                                    <img src="{{ asset('storage/'. $file->file) }}" alt="" width="200px">
                                                </a>
                                            @else
                                                <i class="fa fa-file"></i>
                                                <a href="{{ asset('storage/'. $file->file) }}">{{ $file->originalName }}</a><span></span>
                                            @endif
                                            <br>
                                            <br>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <h4 style="text-align: end">{{ $his->created_at->format('d.m.Y H:i') }}</h4>
                            <h4 style="text-align: end; color: #1ab394">{{ $his->text }}</h4>
                            <hr>
                        @endif
                    @endforeach
                    <hr>
                    {{ Form::panelTextarea('new_comment') }}
                    <div class="form-group">
                        <label for="files[]" class="col-md-4 control-label">Файлы</label>
                        <div class="col-md-8">
                            {!! Form::file('files[]', ['multiple' =>'multiple']) !!}
                        </div>
                    </div>
                @endif
                @if(!$wishes->id)
                    <div class="form-group">
                        <label for="files[]" class="col-md-4 control-label">Файлы</label>
                        <div class="col-md-8">
                            {!! Form::file('files[]', ['multiple' =>'multiple', 'id' => 'upload-file']) !!}
                        </div>
                    </div>
                @endif
            </div>
            <div class="ibox-footer">
                {{ Form::panelButton() }}
                    @if($wishes->id and !$readonly)
                        <a data-href="{{ route('admin.'.$entity.'.delegate', $wishes) }}" data-href-post="{{ route('admin.'.$entity.'.newComment', $wishes) }}" class="delegateStore btn btn-sm btn-primary"> <i class="fa fa-dot-circle-o"></i> @lang('admin.wishes.delegate_send') </a>
                    @endif
                </div>
        </div>
    </div>

<script>
    $(function () {
        $('.changeStatus').on('click', function () {
            var Status = $(this).data('status');
            var url = $(this).data('href');
            var comment = document.getElementById('new_comment').value;
            console.log($(this).data());
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    status: Status,
                    comment: comment
                },
                success: function(data)
                {
                    location.reload()
                }
            });
        });
    });
    $(function () {
        $('.delegateStore').on('click', function () {
            var url = $(this).data('hrefPost');
            var redirect = $(this).data('href');
            var id = document.getElementById('object_id').value;
            var comment = document.getElementById('new_comment').value;
            console.log($(this).data());
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id,
                    comment: comment
                },
                success: function(data)
                {
                    location.replace(redirect)
                }
            });
        });
    });
    @if(!$wishes->id)

        $(document).on('DOMContentLoaded', function() {
          let dt = new DataTransfer();
            $('#upload-file').on('change', function() {
              $('#files-container').remove();
              let files = this.files;
              let filesLength = files.length;
              if(filesLength > 0){
                let parent = $(this).parent().closest('.form-group');
                for(let i = 0; i < filesLength; i++){
                  dt.items.add(new File([files[i]], files[i].name, {type : files[i].type}))
                }
                for(let i = 0; i < dt.files.length; i++){
                  let filesContainer = $('#files-container .form-group');
                  let file = dt.files[i];
                  let src = window.URL.createObjectURL(file);
                  let type = 'image';
                  let append = '<a href="'+src+'" target="_blank"><img src="'+src+'" alt="" width="200px"></a>';
                  if(file.type.indexOf('image') === -1){
                    type = 'file';
                    append = '<i class="fa fa-file"></i><a href="'+src+'">'+file.name+'</a><span></span>'
                  }
                  let id = type === 'image' ? 'type-image' : 'type-file';

                  if(filesContainer.length > 0){
                    let fileType = $('#files-container #'+id);
                    if(fileType.length === 0){
                      filesContainer.append('<div id="'+id+'"></div>')
                    }
                    filesContainer.find('#'+id).append(append);
                  }else{
                    let filesContainerHtml =
                      '<div id="files-container">' +
                          '<div class="form-group">' +
                            '<div id="'+id+'">'+
                                append+
                            '</div>'+
                          '</div>'+
                      '</div>'
                    parent.prepend(filesContainerHtml);
                  }
                }
                this.files = dt.files
              }
            })
        })

    @endif
</script>
