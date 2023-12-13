Kedves {{ $user->name ?? 'Admin' }},

<p>Te, vagy valaki a nevedben sikeres jelszó módosítást hajtott végre.</p>
<p>Amennyiben Te voltál, akkor nincs további teendőd.</p>
<p>Ha nem te voltál, akkor jelezd az esetet az illetékesnek és változtasd meg azonnal a jelszavad, <a href="{{ route('password.request') }}">ide kattintva</a>.</p>
<br>
<p>Szép napot!</p>
