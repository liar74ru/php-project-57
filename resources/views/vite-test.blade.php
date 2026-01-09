<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/app.css'])
</head>
<body>
<h1 style="color: red;">Если этот текст красный - Vite работает!</h1>

<pre>
        {{ print_r(Vite::manifest(), true) }}
    </pre>
</body>
</html>
