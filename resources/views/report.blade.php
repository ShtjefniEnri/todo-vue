<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos Report</title>
</head>
<body>
<h1>Hello, {{ $name }}!</h1>
<p>Attached is the latest Todos report.</p>
<p>Regards,<br>{{ env('MAIL_FROM_NAME') }}</p>
</body>
</html>
