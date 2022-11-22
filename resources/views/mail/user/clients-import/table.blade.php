<table cellpadding="5">
    <thead>
    <tr>
        <th>Номер строки</th>
        <th>Имя</th>
        <th>Телефон</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item['key'] }}</td>
            <td>{{ $item['first_name'] }}</td>
            <td>{{ $item['phone'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>