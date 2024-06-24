<!-- resources/views/emails/newsletter_template.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $template->name }}</title>
</head>
<body>
    <h1>{{ $template->name }}</h1>

    <div>
        {!! $template->content !!} <!-- Assuming your content is HTML formatted -->
    </div>
</body>
</html>
