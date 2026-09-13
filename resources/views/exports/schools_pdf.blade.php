<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Schools Master Repository Export</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #3bc0c3;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            color: #2c3e50;
        }

        .header p {
            margin: 0;
            color: #7f8c8d;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }

        th,
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #dee2e6;
        }

        code {
            font-family: Courier, monospace;
            background-color: #f1f2f6;
            padding: 2px 4px;
            border-radius: 3px;
            color: #e83e8c;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #bdc3c7;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Schools Master Repository</h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">ID</th>
                <th style="width: 25%;">School Code</th>
                <th style="width: 40%;">Facility Name</th>
                <th style="width: 25%;">Assigned District</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($schools as $school)
                <tr>
                    <td>{{ $school->id }}</td>
                    <td><code>{{ $school->school_code }}</code></td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->district->name ?? 'Unassigned' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        System Document Export Management Engine — Page 1 of 1
    </div>

</body>

</html>
