<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
</head>
<body>
<style type="text/css">
    * {
        font-family: "Arial", sans-serif;
        font-size: 15px;
        color: #151515;
    }
    h2 {
        font-size: 20px;
        color: #f7b500;
    }
    h3 {
        font-size: 18px;
        color: #f7b500;
    }
    h4 {
        font-size: 16px;
        text-decoration: underline;
        font-weight: 500;
    }
    p {
        line-height: 22px;
    }
    a {
        color: #f7b500;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table tr {
        text-align: left;
    }
    table td, table th {
        padding: 5px 8px;
    }
    table Tbody td {
        border: #E6E6E6 1px solid;
    }
    table th {
        background-color: #F3F3F3;
    }
</style>
@yield('main')
</body>
</html>