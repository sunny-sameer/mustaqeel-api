<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
    <style>
        /* Your custom styles here */
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $subject }}</h1>
        <div class="content">
            {!! nl2br(e($content)) !!}
        </div>

        @if(isset($data['button_text']) && isset($data['button_url']))
        <div class="button-container">
            <a href="{{ $data['button_url'] }}" class="button">
                {{ $data['button_text'] }}
            </a>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for using our service!</p>
        </div>
    </div>
</body>
</html>
