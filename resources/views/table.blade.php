<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Данные таблицы</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f9f9f9;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .back-button button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .back-button button:hover {
            background-color: #45a049;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        th, td {
            border: 1px solid #e0e0e0;
            padding: 10px 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .pagination {
            margin-top: 25px;
        }

        .pagination svg {
            height: 20px;
        }

        .pagination nav {
            display: flex;
            justify-content: center;
        }

        p {
            color: #555;
        }
    </style>
</head>
<body>
<h2>Тип данных: {{ $type }}</h2>

<div class="back-button">
    <button onclick="window.history.back();">← Назад</button>
</div>

@if($data->count() > 0)
    <table>
        <thead>
        <tr>
            @foreach(array_keys($data[0]->toArray()) as $key)
                <th>{{ $key }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr>
                @foreach($item->toArray() as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $data->appends(['type' => $type])->links() }}
    </div>
@else
    <p>Нет данных для отображения.</p>
@endif

</body>
</html>
