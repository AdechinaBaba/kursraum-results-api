<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exam Results</title>

    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }

        h2 { text-align: center; margin-bottom: 5px; }

        .info {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
        }

        .pass { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>Exam Results</h2>

<div class="info">
    <p><strong>Session:</strong> {{ $session->title }}</p>
    <p><strong>Centre:</strong> {{ $session->center->name }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Table</th>
            <th>Name</th>
            <th>Lesen</th>
            <th>Hören</th>
            <th>Schreiben</th>
            <th>Sprechen</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{ $result['table_number'] }}</td>
                <td>{{ $result['full_name'] }}</td>

                <td>{{ $result['lesen'] }}</td>
                <td>{{ $result['hoeren'] }}</td>
                <td>{{ $result['schreiben'] }}</td>
                <td>{{ $result['sprechen'] }}</td>

                <td>{{ $result['total'] }}</td>

                <td class="{{ $result['status'] === 'Bestanden' ? 'pass' : 'fail' }}">
                    {{ $result['status'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>