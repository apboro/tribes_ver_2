<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>
<body>

<h2>Tinkoff Debug</h2>

<table>
    <tr>
        <th>Дата запроса</th>
        <th>Тело запроса</th>
    </tr>
    @foreach($logs as $log)
    <tr>
        <td>{{ $log['time'] }}</td>
        <td>{{ json_encode($log['data'] ) }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>

