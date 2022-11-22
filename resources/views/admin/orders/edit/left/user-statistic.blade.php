<div class="form-group">
    <table class="table table-bordered text-center">
        <tr>
            <td><i class="fa fa-arrow-right" title="Кол-во поездок" aria-hidden="true"></i></td>
            <td><i class="fa fa-check text-success" title="Кол-во явок" aria-hidden="true"></i></td>
            <td><i class="fa fa-times text-danger" title="Кол-во неявок" aria-hidden="true"></i></td>
            <td><i class="fa fa-ban text-danger" title="Кол-во отмененных броней" aria-hidden="true"></i></td>
        </tr>
        <tr>
            <td>{{$client->orders->where('status', 'active')->count()}}</td>
            <td>{{$client->orders->where('appearance', 1)->where('status', 'active')->count()}}</td>
            <td>{{$client->orders->where('appearance', 0)->where('status', 'active')->count()}}</td>
            <td>{{$client->orders->where('status', 'disable')->count()}}</td>
        </tr>
    </table>
</div>
