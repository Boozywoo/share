@extends('mail.root')

@section('main')

    <h2>Клиенты импортированы</h2>

    @if($countDuplicate = count($duplicates))
        <p>
            Кол-во клиентов, которые уже есть в базе:  {{ $countDuplicate }}
        </p>
        @include('mail.user.clients-import.table', ['items' => $duplicates])
    @endif

    @if($countWrongFirstNames = count($wrongFirstNames))
        <p>
            Кол-во клиентов, у которых не заполнено имя:  {{ $countWrongFirstNames }}
        </p>
        @include('mail.user.clients-import.table', ['items' => $wrongFirstNames])
    @endif

    @if($countWrongPhones = count($wrongPhones))
        <p>
            Кол-во клиентов, с неправильным номером:  {{ $countWrongPhones }}
        </p>
        @include('mail.user.clients-import.table', ['items' => $wrongPhones])
    @endif

@endsection