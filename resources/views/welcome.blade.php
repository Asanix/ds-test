<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Импорт данных</title>
    <style>
        #statusMessage {
            margin-top: 15px;
            font-size: 18px;
        }
    </style>
    <script>
        function updateForms() {
            const type = document.getElementById('dataType').value;

            document.getElementById('fetchForm').dataset.endpoint = `api/data/${type}/fetch`;

            document.getElementById('showLink').href = `api/data/show-table?type=${type}`;
            document.getElementById('deleteForm').action = `api/data/${type}/delete`;
        }

        async function fetchData(event) {
            event.preventDefault();

            const form = document.getElementById('fetchForm');
            const endpoint = form.dataset.endpoint;

            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();

            try {
                const response = await fetch(`${endpoint}?${params}`);
                if (!response.ok) throw new Error('Ошибка при получении данных');

                const result = await response.json();

                document.getElementById('statusMessage').textContent = 'Данные успешно загружены!';
                document.getElementById('statusMessage').style.color = 'green';
            } catch (err) {
                document.getElementById('statusMessage').textContent = 'Ошибка загрузки данных!';
                document.getElementById('statusMessage').style.color = 'red';
            }
        }

        window.onload = function () {
            updateForms();
            document.getElementById('fetchForm').addEventListener('submit', fetchData);
        };
    </script>
</head>
<body>

<h2>Выберите тип данных</h2>
<select id="dataType" onchange="updateForms()">
    <option value="sales">Продажи</option>
    <option value="orders">Заказы</option>
    <option value="incomes">Доходы</option>
    <option value="stocks">Продукты</option>
</select>

<hr>

<form id="fetchForm" method="GET" data-endpoint="">
    <label>С:</label>
    <input name="dateFrom" type="date">
    <label>По:</label>
    <input name="dateTo" type="date">

    <button type="submit">Стянуть данные</button>
</form>

<div id="statusMessage"></div>

<br>

<a id="showLink" href="">Показать данные</a>

<br><br>

<form id="deleteForm" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Удалить данные</button>
</form>

@if (session('success'))
    <div style="color: green; margin-top: 10px;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="color: red; margin-top: 10px;">
        {{ session('error') }}
    </div>
@endif

</body>
</html>
