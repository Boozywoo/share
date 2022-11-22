<style>
    .modal-lg{
        width: 1200px;
    }
</style>
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <h2>{{trans('admin.tours.information')}}</h2>
    <div class="hr-line-dashed"></div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" class="col-xs-4">Остановка туда</th>
                    <th scope="col" class="col-xs-4">Остановка обратно</th>
                    @for($i = 1; $i <= $tour->bus->places; $i++)
                        <th scope="col">Место {{$i}}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($tour->route->stations as $key => $station)
                    @if($tour->route->stations->has($key + 1))
                        <tr>
                            <th scope="row">{{$key + 1}}</th>
                            <td>{{$station->name}}</td>
                            <td>{{$tour->route->stations[$key + 1]->name}}</td>
                            @for($i = 1; $i <= $tour->bus->places; $i++)
                                <td scope="col">
                                    @if($placesTable[$station->id][$i])
                                        <i class="fa fa-user fa-lg @if($placesTable[$station->id][$i] > 1)fa-2x @endif"
                                            @if($placesTable[$station->id][$i] > 1)
                                                style="color: red" class="fa-2x" title="{{ $placesTable[$station->id][$i] }}" 
                                            @else
                                                style="color: {{ $colorsTable[$station->id][$i] }}"
                                            @endif
                                            aria-hidden="true">
                                        </i>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="hr-line-dashed"></div>

