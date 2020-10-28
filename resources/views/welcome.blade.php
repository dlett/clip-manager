<!DOCTYPE html>
<html lang="en" style="height: 100%; width: 100%;">
<head>
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body style="height: 100%; width: 100%;">
    <div style="width: 100%; height: 100%; padding-top: 300px;" class="text-center">
        <h1 class="mb-5">{{ config('app.name') }}</h1>
        <a href="{{ route('login') }}" style="color: #6441a5;">
            <img src="/img/TwitchGlitchPurple.png" width="32" height="32" alt="Twitch Logo"> Login with Twitch
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
            crossorigin="anonymous"></script>
</body>
</html>
