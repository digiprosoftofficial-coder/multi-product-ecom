<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact message</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #333;">
    <h2 style="margin-top: 0;">New contact message</h2>
    <p><strong>Name:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    @if(! empty($data['phone']))
        <p><strong>Phone:</strong> {{ $data['phone'] }}</p>
    @endif
    @if(! empty($data['subject']))
        <p><strong>Subject:</strong> {{ $data['subject'] }}</p>
    @endif
    <p><strong>Message:</strong></p>
    <p style="white-space: pre-wrap;">{{ $data['message'] }}</p>
    <hr>
    <p style="font-size: 12px; color: #666;">Sent from the contact form on {{ $siteName }}.</p>
</body>
</html>
