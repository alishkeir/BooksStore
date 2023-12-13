<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h2>Kedves Felhasználónk!</h2>

<p>Sajnos az utóbbi időben folyamatosan problémát okozott a Facebook belépés, így végül levettük az oldalról ezt a
    lehetőséget.</p>

A mai naptól kezdve a hagyományos bejelentkezést használva tudsz bejelentkezni a fiókodba, az alábbi adatokkal:
<br/>
<strong>- e-mail címed: {{$email}}</strong>
<br/>
<strong>
- jelszó: {{$newPassword}}
</strong>
<p>
    Javasoljuk, hogy adj meg új jelszót az általunk generált jelszó helyett az alábbi linkre kattintva:
    <br/>
    <a href="{{$website}}">{{  $website }}</a>
</p>

<p>
    Az általunk megadott jelszóval, vagy az általad újonnan létrehozott jelszóval a továbbiakban újra be tudsz
    jelentkezni és használni az oldalunkat, immáron zökkenőmentesen.
</p>

Köszönjük a megértést!
<br/>
Szép napot kívánunk:

<br>
<br>
@if ($store === 0)
    az Álomgyár csapata
    <br>
    <a href="mailto:info@alomgyar.hu">info@alomgyar.hu</a>
@else
    az Olcsókönyvek csapata
    <br>
    <a href="mailto:info@olcsokonyvek.hu">info@olcsokonyvek.hu</a>
@endif
</body>
</html>
