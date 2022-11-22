@foreach($buses as $bus)
    @if ($loop->last)

    @endif
    <div class="row" style="padding: 10px">
        <div class="col-md-1 foto">
            <img src="{{asset('rent/img/Car-icon.png')}} " width="64" alt="">
        </div>
        <div class="col-md-2 name">
            <p class="text-black-50">{{$bus->name}} <br></p>
        </div>
        <div class="col-md-9 race">
            @if(!$loop->index) <span style="height: {{($buses->count()-1)*95 +20}}px" class="time"></span> @endif
            <div onselectstart="return false;">
                <div ondrop="drop(event)" ondragover="allowDrop(event)" id="timeslider{{$loop->iteration}}" class="timeSlider">
                </div>
            </div>
        </div>
    </div>
@endforeach