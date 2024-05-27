<!DOCTYPE html>
<html>
<head>
    <title>Participant Result</title>
</head>
<body>
    <h1>Result</h1>
    <p>Name: {{ $participant->name }}</p>
    <p>TIU: {{ $participant->tiu }}</p>
    <p>TWK: {{ $participant->twk }}</p>
    <p>TKP: {{ $participant->tkp }}</p>
    {{-- <p>Total Score: {{ $participant->total_score}}</p> --}}
    <p>Status: {{ $status }}</p>
    <p>Method: {{ $method }}</p>
</body>
</html>