@if(!empty($repairCardList))
    <div class="form-group margin-disable ibox-content-item">
        <select name=""  id="select-template" class="form-control ibox-content-item">
            <option selected disabled value="">{{__('admin_labels.repair_card_template_id')}}</option>
            @foreach($repairCardList as $item)
                <option id="card_{{$item->id}}" value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
        {{Form::hidden('cards',null, ['id' => 'card-list'])}}

        <p class="error-block"></p>
        <div id="cards-area">

        </div>
    </div>

    <script>
        $(document).ready(function () {

            $("#select-template").select2();
        });
    </script>
@endif



<script>
    var cards = [];

</script>
<script>
    function removeCard(id) {
        cards = cards.filter((item) => {
            return item.id != id;
        });
        $('#card_'+id).prop('disabled', '');
        $("#select-template").select2();

        updateList();
    }

    function updateList() {

        $('#card-list').val(JSON.stringify(cards.map((item) => item.id)));
        let list_html = '';
        console.log(cards);
        cards.forEach((item) => {
            if (item.name && !item.text) {
                item.text = item.name;
            }
            list_html += '<div class="list-item">' + item.text + '<div class="list-item-remove" onclick="removeCard(' + item.id + ')">x</div></div>';
        });
        $('#cards-area').html(list_html);
    }

    $(document).ready(function () {


        $("#select-template").select2();

        $('#select-template').on("select2:select", function (e) {
            let selected_item = e.params.data;
            let element = e.params.data.element;
            cards = cards.filter((item) => {
                if (item.id == selected_item.id) {
                    return false;
                }
                return true;
            });
            cards.push(selected_item);
            $('#card_'+selected_item.id).prop('disabled','disabled');
            $('#select-template').val('');
            $("#select-template").select2();
            updateList();
            this.selectedIndex = 0;

        });


    })
    let item;
</script>

@if(!empty($repair) && $repair->card_templates)
    @foreach($repair->card_templates as $card)
        <script>
            item = {!! json_encode($card) !!};
            cards.push(item);
            $('#card_'+item.id).prop('disabled','disabled');
            updateList();
        </script>
    @endforeach
@endif