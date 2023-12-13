<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Álomgyár

# Telepítés

## 1 Előkészület

1.  `git clone [repo_url]`
2.  .env.local átnevezése .env-re
3.  .env-ben beállítod az admin email címét és az admin usernevet (azaz a sajátod, ahova majd a megerősítő emailt akarod kapni)

## 2 Indítsd el a containereket

1.  Ha még nem tetted meg, jelentkezz be a registry.gitlab.com-ra: `docker login registry.gitlab.com`

2.  Navigálj a repo mappához és add ki a parancsot: `docker-compose up -d` (-d azért kell, hogy ne foglalja be a terminálodat)

3.  `docker exec -it alomgyar-web composer install` | Docker-compose környezet esetén docker exec -it ContainerID vagy Container-name composer install

## 3 Használat

Ha minden jól megy, a `composer install` után automatikusan lefut az első migráció és a superadmin létrehozása. A /gephaz oldalra az első autentikálásnál kattints az elfelejtett jelszóra.

Az oldal elérhető a localhost:22200 címen illetve localhost:22200/gephaz címen

# SKVADMIN

## Welcome package

Említésre érdemes 3rd party:

-   spatie/media-library https://spatie.be/docs/laravel-medialibrary/v9/introduction
-   laravel/fortify https://laravel.com/docs/8.x/fortify (Auth)
-   laravel/sanctum https://laravel.com/docs/8.x/sanctum

## Package létrehozása

Az items package egy alap csomag, ami tartalmaz egy composer.json-t, ServiceProvider-t, controllert, modelt, a model migrációját, view-kat a crud-hoz, és a route-ot. A route alapértelmezetten az admin alá van beregisztrálva, konfigurációtól függően beregisztrálhatod a frontendre is.

Új csomag létrehozásánál másold le a teljes mappát, nevezd át a namespace-eket és mindent, ami az Item-el kapcsolatos, a csomagod regisztráld be a root composer.json-ban az autoload/psr-4 alá:

`"Skvadcom\\Csomagod\\": "packages/skvadcom/csomagmappa/src/"`

Az app/config/app.php-ban regisztráld be a ServiceProvider-t:

`Skvadcom\Csomagod\CsomagServiceProvider::class,`

Futtasd le a `composer dump-autoload` command-ot, migrációs fájl esetén pedig a `php artisan migrate` command-ot is.

# Activity Log

A rendszerben van egy activity log, amivel minden user aktivitás elmenthető a db-be. Ennek a dokumentációját itt találod:
https://docs.spatie.be/laravel-activitylog/v3/introduction/

# Log viewer

Tettem bele egy log viewer is https://github.com/rap2hpoutre/laravel-log-viewer
A projekt.hu/gephaz/logs címen érhető el egyelőre

# Changelog

## 2021-03-17

-   A master.blade.php-be bekerült egy spinner <x-admin::loader />
    A loader.blade.php a Module/Admin/Resources/Views/components mappában van
    Stílus a skvadmin.css-ben
-   Elkezdtem a mindig előforduló kifejezéseket a resources/lang/hu/general.php fájlban gyűjteni, hogy könnyen fordítható legyen
-   A kép(ek) feltöltéséről az UploadImageComponent gondoskodik. Működik a drag'n'drop feltöltés is
-   Van egy Dropzone options objektum a custom.js fájlban, ha valaki azzal akarna operálni. Egyelőre ki van kommentelve.
-   A drag'n'drop képfeltöltés miatt a summernote dragandrop funkcionalitást le kell tiltani
-   Van egy toast-message komponens a pnotify kiváltására. A window.toast-message event triggereli. Üzenet átadható neki.
    Lehet livewire komponensben `$this->dispatchBrowserEvent('toast-message', ['message' => 'Hiba történt!', 'type' => 'error']);`
    Lehet javascriptben (lásd livewire dokumentáció)

# Funkcionalitás - mi/hol/merre

## Customer autentikáció

A Customer package kezeli backenden a Customerekkel kapcsolatos CRUD operációkat. Ezen felül ide fogom bevezetni a FE-del
kapcsolatos akciókat is, mivel a Fortify csak a User entitással hajlandó együttműködni és felül kell írnom a fájlok 99%-át.
Szóval a Customer Journey és a hozzá kapcsolódó Controllerek/Actionök/Eventek/Listenerek
Az endpointok a Routes/api.php és Routes/web.php fájlokban vannak deklarálva.
CSRF védelem érdekében kihasználjuk a Sanctum által nyújtott CSRF védelmet, aminek FE implementálása 1 sor és itt található
(https://laravel.com/docs/8.x/sanctum#csrf-protection)

### Regisztráció emaillel

Az api endpoint az api/{store}/register, ahol a store: 0 - alomgyar, 1 - olcsokonyvek, 2 - nagyker
Ezt a Alomgyar\Customers\CustomerAuthController@store funkció kezeli, itt meghívjuk az Alomgyar\Customers\Actions\CreateNewCustomer
class-t, ami elvégzi a validálást és létrehozza a vevőt, majd kiküldi az email verifikációs emailt (átírható a
Notifications\CustomerVerifyEmail fájlban).

A kiküldött verifikációs emailben rákattint a linkre. Ha minden rendben, akkor átirányítjuk valahova. Ezt a packages/alomgyar/customers/CustomerVerifyEmailController.php
végzi és hívja meg a CustomerVerified eventet, amire a SendVerifiedEmailToCustomer listener hallgat.

### Bejelentkezés

A beléptetés az api/{store}/login endpoint meghívásával történik. Ezt a Alomgyar\Customers\CustomerAuthController@login funkció kezeli,
ami az Actions\AttemptToAuthenticate class-t hívja meg.
Itt többrétegű validálás van:

1.  Lekérjük, hogy az email címmel létezik-e customer
2.  Ha van Bearer token, de nem egyezik a DB-ben találhatóval VAGY nincs Bearer token, de a customer már authentikálva lett, akkor "Something
    went wrong" hibaüzenet jön vissza
3.  Ha van Bearer token és a customer tokenével egyezik, az azt jelenti, hogy már belépett
4.  Ha az email/store páros alapján nincs ilyen Customer VAGY a jelszó nem egyezik, akkor auth failed hibaüzenet jön vissza
5.  Fentiek egyike sem igaz, akkor sikeres a belépés és visszaküldjük a tokent, ami jelenleg 2 óráig él és a valid_until értékét

### Elfelejtett jelszó

Egy POST requestet küldünk az api/{store}/forgot-password endpointra, aminek tartalma a Customer email címe. Ezt a
\Laravel\Fortify\Http\Controllers\PasswordResetLinkController@store method kezeli. Ha van ilyen Customer, akkor küld egy emailt a
jelszó megváltoztatásához szükséges utasításokkal és egy linkkel.
A linkre kattintva egy formra fog jutni az illető, ezt a FE kezeli. A FE form a api/{store}/update-password endpointot PUT requesttel
hívja meg, aminek tartalma password, password_confirmation, token, email.
A sikeres jelszó módosítással kiléptetjük és az auth tokenjét töröljük a db-ből.
Ezután a Customer email értesítést kap a sikeres jelszó módosításról.

### Kilépés

Egy POST requestet küldünk az api/{store}/logout endpointra, aminek tartalma a Customer email címe. Ezt a Alomgyar\Customers\CustomerAuthController@destroy
funkció kezeli. A request ellenőrzi a bearerTokent, és az email cím meglétét. Ha minden megvan, akkor kilépteti, auth tokent törli
és Success message-gel tér vissza, ellenkező esetben Errors tömb jön.

## Attribútumok & Infok

A könyvek sorrendjénél a most-popular = a product tábla orders_count sorrendjével.

Egy termék is_new, ha van valami 4 nap. Meg ilyenek. Mert visszakerül.

A shipping address lehet beállított cím a customer-hez. Lehet bolt címe. És lehet csomagpont címe.

A username a vevő keresztneve vagy az email címének kukac előtti része.
