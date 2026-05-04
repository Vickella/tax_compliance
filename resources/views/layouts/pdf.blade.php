<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'PDF Document')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .pdf-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .pdf-header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #333;
        }

        .pdf-header h2 {
            font-size: 18px;
            margin: 0 0 5px 0;
            color: #666;
        }

        .pdf-header p {
            margin: 0;
            color: #666;
        }

        .pdf-content {
            margin-bottom: 40px;
        }

        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .pdf-table th,
        .pdf-table td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
        }

        .pdf-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .pdf-footer {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .pdf-footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>