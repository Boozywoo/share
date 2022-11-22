@extends('mail.layouts.main')

@section('content')

    <h2>Подтвеждение регистрации на сайте </h2>

    <p>
        Спасибо что воспользовались нашими услугами <br>
        Ваша регистрация прошла успешно <br>
        @date($client->created_at) {{ $client->created_at->format('H:i') }}
    </p>

    <h3>Информация о пользователе</h3>
    <table>
        <tbody>
        <tr>
            <td>Имя</td>
            <td>{{ $client->first_name }}</td>
        </tr>
        @if($client->email)
            <tr>
                <td>Email</td>
                <td>{{ $client->email }}</td>
            </tr>
        @endif
        <tr>
            <td>Пароль</td>
            <td>{{ $password }}</td>
        </tr>
        @if($client->phone)
            <tr>
                <td>Телефон (логин)</td>
                <td>@phone($client->phone)</td>
            </tr>
        @endif
        <tr>
            <td>Дата регистрации</td>
            <td>@date($client->created_at)</td>
        </tr>
        @if($client->status_id)
            <tr>
                <td>Льгота пользователя</td>
                <td>{{ $client->socialStatus->name }}</td>
            </tr>
        @endif
        </tbody>
    </table>

@endsection