<html>
<head>
    <title>Recuperació de contrasenya</title>
</head>
<body>    <p>Hola,</p>
    <p>Fes clic a l'enllaç de sota per restablir la teva contrasenya:</p>
    <p><a href="{{ route('password.reset', ['token' => $token]) }}?email={{ $email }}">Restablir contrasenya</a></p>
    <p>Si no has sol·licitat un canvi de contrasenya, si us plau ignora aquest correu.</p>
    <p>Gràcies,</p>
    <p>L'equip de suport</p>
</body>
</html>
