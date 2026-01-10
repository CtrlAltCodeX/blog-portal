<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h3 {
            text-align: center;
            margin-bottom: 5px;
        }
        .date-time {
            text-align: center;
            font-size: 10px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h3>Publishers List</h3>

<div class="date-time">
    Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Publisher</th>
            <th>List of Affected Listings</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listings as $index => $listing)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $listing->publisher }}</td>
                <td>{{ $listing->publisherCount($listing->publisher) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
