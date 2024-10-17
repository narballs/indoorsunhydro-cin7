<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Reviews</title>
</head>
<body>
    <h1>Google Reviews</h1>

    @if(!empty($reviews))
        <ul>
            @foreach($reviews as $review)
                <li>
                    <strong>{{ $review['author_name'] }}</strong> (Rating: {{ $review['rating'] }}/5)
                    <p>{{ $review['text'] }}</p>
                    <p><small>{{ \Carbon\Carbon::parse($review['time'])->format('F j, Y') }}</small></p>
                </li>
            @endforeach
        </ul>
    @else
        <p>No reviews found.</p>
    @endif
</body>
</html>
