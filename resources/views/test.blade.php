<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reverb Real-Time Test</title>
</head>
<body>
@vite('resources/js/app.js') <!-- Ensure this path is correct -->


    <script src="{{ asset('js/app.js') }}" defer></script>


<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
            window.Echo.channel('test-channel')
            .listen('.message-sent', (e) => { // Match the event name here
                    console.log('Message received:', e.message);
                    document.getElementById('message').innerText = e.message;
                });

    });
</script>

<div id="message"></div> <!-- Ensure this div exists for message display -->



</body>
</html>
