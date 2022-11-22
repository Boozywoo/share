@permission('view.orders')
    <div class="form-group">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
            </div>
            <img src="images/{{ Session::get('image') }}">
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2>
            Дневные изображения
        </h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            @foreach ($imagesAM as $image) 
                <div class="col-md-4">
                    <div class="thumbnail img-wraps">
                        <span  class="closes" title="Delete" path="am/{{ $image->getFilename() }}">&times;</span>
                        <img src="{{ asset('assets/index/images/for_clients/am/' . $image->getFilename()) }}" class="img-responsive">
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('admin.settings.clients_interface_settings.upload', ['time' => 'am']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">

                <div class="col-md-6">
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Загрузить</button>
                </div>

            </div>
        </form>

        <br>

        <h2>
            Ночные изображения
        </h2>
        <div class="hr-line-dashed"></div>

        <div class="row">
            @foreach ($imagesPM as $image) 
                <div class="col-md-4">
                    <div class="thumbnail img-wraps">
                        <span  class="closes" title="Delete" path="pm/{{ $image->getFilename() }}">&times;</span>
                        <img src="{{ asset('assets/index/images/for_clients/pm/' . $image->getFilename()) }}" class="img-responsive">
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('admin.settings.clients_interface_settings.upload', ['time' => 'pm']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">

                <div class="col-md-6">
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Загрузить</button>
                </div>

            </div>
        </form>

        <br>

        <h2>
            Изображения для моб. устройств
        </h2>
        <div class="hr-line-dashed"></div>

        <div class="row">
            @foreach ($imagesMobile as $image) 
                <div class="col-md-4">
                    <div class="thumbnail img-wraps">
                        <span  class="closes" title="Delete" path="mobile/{{ $image->getFilename() }}">&times;</span>
                        <img src="{{ asset('assets/index/images/for_clients/mobile/' . $image->getFilename()) }}" class="img-responsive">
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('admin.settings.clients_interface_settings.upload', ['time' => 'mobile']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">

                <div class="col-md-6">
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Загрузить</button>
                </div>

            </div>
        </form>
    </div>
@endpermission