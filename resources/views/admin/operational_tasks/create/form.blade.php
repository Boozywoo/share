    <div class="ibox-content-custom">

        <div class="row">
            <h4>Задача</h4>

            <div class="col-md-12">
                {{ Form::panelSelect('responsible', $responsibles, null,  ['class'=>'js-user-type-single form-control']) }}
                {{ Form::panelText('subject', null, null) }}

                {{ Form::panelTextarea('description',false, null, null) }}
                    <div class="form-group">
                        <label for="files[]" class="col-md-4 control-label">Файлы</label>
                        <div class="col-md-8">
                            {!! Form::file('files[]', ['multiple' =>'multiple', 'id' => 'upload-file']) !!}
                        </div>
                    </div>
            </div>
            <div class="ibox-footer">
                {{ Form::panelButton() }}
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

        $(document).on('DOMContentLoaded', function() {
            $('#upload-file').on('change', function() {
              $('#files-container .form-group').empty();
              let files = this.files;
              let filesLength = files.length;
              if(filesLength > 0){
                let parent = $(this).parent().closest('.form-group');
                for(let i = 0; i < filesLength; i++){
                  let filesContainer = $('#files-container .form-group');
                  let file = files[i];
                  let src = window.URL.createObjectURL(file);
                  let append = '<a href="'+src+'" target="_blank"><img src="'+src+'" alt="" width="200px"></a>';
                  if(file.type.indexOf('image') === -1){
                    append = '<i class="fa fa-file"></i><a href="'+src+'">'+file.name+'</a><span></span>'
                  }
                  if(filesContainer.length > 0){
                    filesContainer.append(append);
                  }else{
                    let filesContainerHtml =
                      '<div id="files-container">' +
                          '<div class="form-group">' +
                            append+
                          '</div>'+
                      '</div>'
                    parent.prepend(filesContainerHtml);
                  }
                }
              }
            })
        })

</script>
