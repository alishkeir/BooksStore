<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedcategories = [
            ['regeny', 'Regény'],
            ['sci-fi', 'Sci-fi'],
            ['fantasy', 'Fantasy'],
            ['romantikus', 'Romantikus'],
            ['koenyv', 'Könyv'],
            ['lanyregeny', 'Lányregény'],
            ['tankoenyvek-segedkoenyvek', '"Tankönyvek, segédkönyvek"'],
            ['segedkoenyv', 'Segédkönyv'],
            ['tudomanyos-koenyvek', 'Tudományos könyvek'],
            ['szociologia', 'Szociológia'],
            ['biologiaallatok', 'Biológia(Állatok)'],
            ['gasztronomia', 'Gasztronómia'],
            ['borok', 'Borok'],
            ['gyermek-es-ifjusagi', 'Gyermek és ifjúsági'],
            ['ifjusagi-irodalom', 'Ifjúsági irodalom'],
            ['szepirodalom', 'Szépirodalom'],
            ['novella-elbeszeles', '"Novella, elbeszélés"'],
            ['krimi', 'Krimi'],
            ['essze', 'Esszé'],
            ['akcio-krimi', 'Akció krimi'],
            ['toertenelmi-regeny', 'Történelmi regény'],
            ['antologiakoelteszet', 'Antológia(Költészet)'],
            ['toertenelmi', 'Történelmi'],
            ['vallastoertenet', 'Vallástörténet'],
            ['gyerekversek', 'Gyerekversek'],
            ['eletmod', 'Életmód'],
            ['egeszseges-eletmod', 'Egészséges életmód'],
            ['szakacskoenyv', 'Szakácskönyv'],
            ['desszertek', 'Desszertek'],
            ['vegetarianus', 'Vegetáriánus'],
            ['pszichologia', 'Pszichológia'],
            ['muveszet', 'Művészet'],
            ['eletrajz', 'Életrajz'],
            ['hiressegek', 'Hírességek'],
            ['szakkoenyvek', 'Szakkönyvek'],
            ['ezoteria', 'Ezotéria'],
            ['magia-okkultizmus', '"Mágia, okkultizmus"'],
            ['kepeskoenyvek-lapozok', '"Képeskönyvek, lapozók"'],
            ['sport', 'Sport'],
            ['fitness', 'Fitness'],
            ['mesekoenyv', 'Mesekönyv'],
            ['szorakoztato-irodalom', 'Szórakoztató irodalom'],
            ['tarsadalomtoertenet', 'Társadalomtörténet'],
            ['szerzo-oesszes-es-valogatott-muveikoelteszet', 'Szerző összes és válogatott művei(Költészet)'],
            ['szamitastechika-internet', '"Számítástechika, Internet"'],
            ['nyelvkoenyv-szotar', '"Nyelvkönyv, szótár"'],
            ['koennyitett-olvasmany', 'Könnyített olvasmány'],
            ['utazas', 'Utazás'],
            ['utikoenyv', 'Útikönyv'],
            ['politikusok', 'Politikusok'],
            ['csalad-es-szuelok', 'Család és szülők'],
            ['terhesseg-szueles', '"Terhesség, szülés"'],
            ['foglalkoztato-fuezet-betuk-szamok', 'Foglalkoztató füzet betűk-számok'],
            ['orvos-regeny', 'Orvos regény'],
            ['nyelvtan', 'Nyelvtan'],
            ['kard-es-varazslat-fantasy', 'Kard és varázslat fantasy'],
            ['orvosi', 'Orvosi'],
            ['esemenytoertenet', 'Eseménytörténet'],
            ['eletstrategia', 'Életstratégia'],
            ['gazdasagi-koezeleti-politikai', '"Gazdasági, közéleti, politikai"'],
            ['penzuegyi', 'Pénzügyi'],
            ['rendorsegi', 'Rendőrségi'],
            ['labdarugas', 'Labdarúgás'],
            ['ezoterikus-elmelet', 'Ezoterikus elmélet'],
            ['levelezes-naplo', '"Levelezés, napló"'],
            ['tovabbtanulas', 'Továbbtanulás'],
            ['kaland', 'Kaland'],
            ['akcio-kaland', 'Akció kaland'],
            ['filozofia', 'Filozófia'],
            ['csaladregeny', 'Családregény'],
            ['technikai-sportok', 'Technikai sportok'],
            ['ifjusagi-ismeretterjeszto', 'Ifjúsági ismeretterjesztő'],
            ['foglalkoztato-fuezet-kifesto-szinezo', '"Foglalkoztató füzet, kifestő-szinező"'],
            ['termeszetgyogyasz', 'Természetgyógyász'],
            ['noknek', 'Nőknek'],
            ['joslas-alomfejtes', '"Jóslás, álomfejtés"'],
            ['muvelodestoertenet-kulturtoertenet', '"Művelődéstörténet, kultúrtörténet"'],
            ['idegen-nyelvu-szotar', 'Idegen nyelvű szótár'],
            ['gyerek-nyelvkoenyv', 'Gyerek nyelvkönyv'],
            ['vers-eposzkoelteszet', 'Vers-eposz(Költészet)'],
            ['kerekparozas', 'Kerékpározás'],
            ['aforizmak-gondolatok', '"Aforizmák, gondolatok"'],
            ['humor', 'Humor'],
            ['gyerekneveles', 'Gyereknevelés'],
            ['soetet-fantasy', 'Sötét fantasy'],
            ['szinmu', 'Színmű'],
            ['jog', 'Jog'],
            ['misztikus-kaland', 'Misztikus kaland'],
            ['haborus-kaland', 'Háborús kaland'],
            ['nemzetkoezi-konyha', 'Nemzetközi konyha'],
            ['erotikus', 'Erotikus'],
            ['politika-kormanyzas', '"Politika, kormányzás"'],
            ['homeopatia', 'Homeopátia'],
            ['elmenybeszamolok-utleirasok-naplok', '"Élménybeszámolók, útleírások, naplók"'],
            ['kezikoenyv', 'Kézikönyv'],
            ['koezepiskolai-tankoenyv', 'Középiskolai tankönyv'],
            ['horror', 'Horror'],
            ['mondokak', 'Mondókák'],
            ['csaladi-kapcsolatok', 'Családi kapcsolatok'],
            ['hadtoertenelem', 'Hadtörténelem'],
            ['tarsadalomtudomany', 'Társadalomtudomány'],
            ['vallas-mitologia', '"Vallás, mitológia"'],
            ['altalanos-nyelvkoenyv', 'Általános nyelvkönyv'],
            ['nyelvvizsga', 'Nyelvvizsga'],
            ['marketing-reklam', '"Marketing, reklám"'],
            ['biologiaaz-ember', 'Biológia(Az ember)'],
            ['hobbi-szabadido', '"Hobbi, szabadidő"'],
            ['haziallatok-allatgondozas', '"Háziállatok, állatgondozás"'],
            ['szepsegapolas', 'Szépségápolás'],
            ['matematika', 'Matematika'],
            ['lovaglas', 'Lovaglás'],
            ['kulturtoertenet', 'kultúrtörténet'],
            ['koetes-horgolas', 'Kötés-horgolás'],
            ['altalanos', 'Általános'],
            ['kepes-album', 'Képes album'],
            ['toertenelmi-szemelyisegek', 'Történelmi személyiségek'],
            ['gazdasagtoertenet', 'Gazdaságtörténet'],
            ['foglalkoztato-fuezet-matricas', '"Foglalkoztató füzet, matricás"'],
            ['horgaszat', 'Horgászat'],
            ['tarsalgasi-nyelvkoenyv', 'Társalgási nyelvkönyv'],
            ['kreativ-hobby', 'Kreatív Hobby'],
            ['magyar-konyha', 'Magyar konyha'],
            ['kezimunka', 'Kézimunka'],
            ['konyhai-praktikak', 'Konyhai praktikák'],
            ['szabas-varras', 'Szabás-varrás'],
            ['italok-koktelok', '"Italok, koktélok"'],
            ['taplalkozas', 'Táplálkozás'],
            ['parkapcsolat', 'Párkapcsolat'],
            ['kertepites', 'Kertépítés'],
            ['naploregeny', 'Naplóregény'],
            ['dietas', 'Diétás'],
            ['foiskolai-egyetemi-tankoenyv', '"Főiskolai, egyetemi tankönyv"'],
            ['paranormalis-misztikus-fantasy', '"Paranormális, misztikus fantasy"'],
            ['ifjusagi-regeny', 'Ifjúsági regény'],
            ['magandetektivek', 'Magándetektívek'],
            ['titkosuegynoekoek', 'Titkosügynökök'],
            ['karrier', 'Karrier'],
            ['vallasfilozofia-vallaselmelet', '"Vallásfilózofia, valláselmélet"'],
            ['gyermek', 'Gyermek'],
            ['egeszseges-taplalkozas', 'Egészséges táplálkozás'],
            ['gazdasag', 'Gazdaság'],
            ['dvd', 'DVD'],
            ['specialis', 'Speciális'],
            ['hr', 'HR'],
            ['csillagaszat-urkutatas', '"Csillagászat, űrkutatás"'],
            ['szorakozas', 'Szórakozás'],
            ['zeneszerzok-muzsikosok', '"Zeneszerzők, muzsikosok"'],
            ['kerti-noevenyek', 'Kerti növények'],
            ['vallalkozas', 'Vállalkozás'],
            ['kereskedelem', 'Kereskedelem'],
            ['toertenelmi-eletrajzok', 'Történelmi életrajzok'],
            ['lexikon-enciklopedia', '"Lexikon, enciklopédia"'],
            ['publicisztika', 'Publicisztika'],
            ['szinhaz-film', '"Színház, film"'],
            ['bunuegyi-kaland', 'Bűnügyi kaland'],
            ['hangoskoenyv', 'Hangoskönyv'],
            ['romantikus-regeny', 'Romantikus regény'],
            ['leporello', 'Leporello'],
            ['neprajz', 'Néprajz'],
            ['gyakorlati-tanacsok', 'Gyakorlati tanácsok'],
            ['gyermekirodalom', 'Gyermekirodalom'],
            ['nyelvtudomany', 'Nyelvtudomány'],
            ['toertenelmi-romantikus', 'Történelmi romantikus'],
            ['befozes', 'Befőzés'],
            ['asztrologia', 'Asztrológia'],
            ['keleti-tanitas', 'Keleti tanítás'],
            ['pszihologia', 'Pszihológia'],
            ['brit-detektivregeny', 'Brit detektívregény'],
            ['kisregeny', 'Kisregény'],
            ['kuezdosportok', 'Küzdősportok'],
            ['buddhizmus', 'Buddhizmus'],
            ['romantikus-kisregeny', 'Romantikus kisregény'],
            ['bonsai', 'Bonsai'],
            ['feldobox', 'Feldobox'],
            ['humoros-kaland', 'Humoros kaland'],
            ['irok-koeltok', '"Írők, költők"'],
            ['feng-shui', 'Feng Shui'],
            ['biologianoevenyek', 'Biológia(Növények)'],
            ['young-adult', 'Young Adult'],
            ['tudosok', 'Tudosók'],
            ['nyerskonyha', 'Nyerskonyha'],
            ['toertenelmi-fantasy', 'Történelmi fantasy'],
            ['diafilm', 'Diafilm'],
            ['fenykepezes', 'Fényképezés'],
            ['riportregeny', 'Riportregény'],
            ['turazas-hegymaszas', '"Túrázás, hegymászás"'],
            ['barkacsolas', 'Barkácsolás'],
            ['thriller', 'Thriller'],
            ['drama', 'Dráma'],
            ['oenismeret-oenfejlesztes', '"Önismeret, önfejlesztés"'],
            ['angol-humor', 'Angol humor'],
            ['bunuegyi-romantikus', 'Bűnügyi-romantikus'],
            ['parkapcsolat-szerelem', '"Párkapcsolat, szerelem"'],
            ['ferfiaknak', 'Férfiaknak'],
            ['ismeretterjeszto', 'Ismeretterjesztő'],
            ['paranormal', 'Paranormal'],
            ['paratudomany', 'Paratudomány'],
            ['muelemzes', 'Műelemzés'],
            ['monografia', 'Monográfia'],
            ['gyogyito-ero', 'Gyógyító erő'],
            ['biblia-es-egyeb-szakralis-szoevegek', 'Biblia és egyéb szakrális szövegek'],
            ['muforditaskoelteszet', 'Műfordítás(Költészet)'],
            ['media', 'Média'],
            ['judaizmus', 'Judaizmus'],
            ['muveszek', 'Művészek'],
            ['termeszetfeletti', 'Természetfeletti'],
            ['foeldrajz-geologia-meteorologia', '"Földrajz, Geológia, Meteorólogia"'],
            ['meditacio', 'Meditáció'],
            ['munkak-a-kertben', 'Munkák a kertben'],
            ['betegsegek', 'Betegségek'],
            ['joga', 'Jóga'],
            ['fizika', 'Fizika'],
            ['regeszet', 'Régészet'],
            ['keresztenyseg', 'Kereszténység'],
            ['tematikus', 'Tematikus'],
            ['sakk', 'Sakk'],
            ['epikus-fantasy', 'Epikus fantasy'],
            ['kosarlabda', 'Kosárlabda'],
            ['alternativ-gyogymodok', 'Alternatív gyógymódok'],
            ['politika-politologia', '"Politika, politológia"'],
            ['foglalkoztato-fuezet-logikai', '"Foglalkoztató füzet, logikai"'],
            ['hazipatika', 'Házipatika'],
            ['belyeggyujtes-numizmatika', '"Bélyeggyűjtés, numizmatika"'],
            ['nyelveszet', 'Nyelvészet'],
            ['katonai', 'Katonai'],
            ['pedagogia', 'Pedagógia'],
            ['menedzserkoenyv', 'Menedzserkönyv'],
            ['kepregenyek', 'Képregények'],
            ['parapszichologia', 'Parapszichológia'],
            ['pr-kommunikacio', 'PR-kommunikáció'],
            ['europa-europai-unio', '"Európa, Európai Unió"'],
            ['humoros-krimi', 'Humoros krimi'],
            ['vallasos', 'Vallásos'],
            ['technika', 'Technika'],
            ['ifjusagi', 'Ifjúsági'],
            ['lektur', 'Lektűr'],
            ['szobanoevenyek', 'Szobanövények'],
            ['szakszotarak', 'Szakszótárak'],
            ['lakberendezes-belsoepiteszet', '"Lakberendezés, belsőépítészet"'],
            ['kemregeny', 'Kémregény'],
            ['6-10-ev', '6-10 év'],
            ['olimpiak', 'Olimpiák'],
            ['divat', 'Divat'],
            ['szonoklat', 'Szónoklat'],
            ['controlling', 'Controlling'],
            ['masszazs', 'Masszázs'],
            ['vizi-sportok', 'Vízi sportok'],
            ['hazepites-felujitas', '"Házépítés, felújítás"'],
            ['papir-iroszer', '"Papír, írószer"'],
            ['eletrajzok', 'Életrajzok'],
            ['babanaplo', 'Babanapló'],
            ['ujsag', 'Újság'],
            ['naptarak', 'Naptárak'],
            ['terkep', 'Térkép'],
            ['tarsasjatek', 'Társasjáték'],
            ['uennepek', 'Ünnepek'],
            ['karikatura', 'Karikatúra'],
            ['allatos-kaland', 'Állatos kaland'],
            ['tarot', 'Tarot'],
            ['magyar-toertenelem', 'Magyar történelem'],
            ['gyermekegeszseg', 'Gyermekegészség'],
            ['allatregeny', 'Állatregény'],
            ['egyetemes-toertenelem', 'Egyetemes Történelem'],
            ['oekologia-koernyezetvedelem', '"Ökológia, környezetvédelem"'],
            ['epiteszet', 'Építészet'],
            ['epiteszek', 'Építészek'],
            ['egyeni-sportok', 'Egyéni sportok'],
            ['kepzomuveszek', 'Képzőművészek'],
            ['oenkepzes', 'Önképzés'],
            ['ezoterikus-tanitasok', 'Ezoterikus tanítások'],
            ['kemia-vegyeszet', '"Kémia, vegyészet"'],
            ['nepkoelteszetkoelteszet', 'Népköltészet(Költészet)'],
            ['ertelmezo', 'Értelmező'],
            ['vadaszat', 'Vadászat'],
            ['vadaszat-horgaszat', '"Vadászat, horgászat"'],
            ['iszlam', 'Iszlám'],
            ['biokerteszet', 'Biokertészet'],
            ['altalanos-iskolai-tankoenyv', 'Általános iskolai tankönyv'],
            ['szenvedelybetegsegek', 'Szenvedélybetegségek'],
            ['harry-potter', 'Harry Potter'],
            ['karacsony', 'Karácsony'],
            ['hinduizmus', 'Hinduizmus'],
            ['cd', 'CD'],
            ['kartyak', 'Kártyák'],
            ['biologiagenetika', 'Biológia(Genetika)'],
            ['altalanosevkoenyvek', 'Általános(Évkönyvek)'],
            ['atletika', 'Atlétika'],
            ['glutenmentes', 'Gluténmentes'],
            ['jarmuvezetes', 'Járművezetés'],
            ['taskak', 'Táskák'],
            ['mitologia', 'Mitólogia'],
            ['vadaszkaland', 'Vadászkaland'],
            ['muszaki-tankoenyv', 'Műszaki tankönyv'],
            ['14-18-ev', '14-18 év'],
            ['laktozmentes', 'Laktózmentes'],
            ['numerologia', 'Numerólogia'],
            ['koezgazdasagtan', 'Közgazdaságtan'],
            ['matrica', 'Matrica'],
            ['riport', 'Riport'],
            ['modellezes', 'Modellezés'],
            ['viragkoeteszet', 'Virágkötészet'],
            ['humoreszk', 'Humoreszk'],
            ['0-2-ev', '0-2 év'],
            ['nyelv', 'Nyelv'],
            ['uj-kategoria', 'Új kategória'],
            ['teli-sportok', 'Téli sportok'],
            ['vega', 'Vega'],
            ['vegan', 'Vegán'],
            ['csomagok', 'Csomagok'],
            ['liedloff', 'liedloff'],
            ['alomcsomagok', 'Álomcsomagok'],
            ['szivdogleszto-kedvezmenyek', 'szívdöglesztő kedvezmények'],
            ['tudomany', 'Tudomány'],
            ['eletrajzi', 'Életrajzi'],
            ['kommunikacio', 'Kommunikáció'],
            ['riport-publicisztika', '"Riport, publicisztika"'],
            ['jatek', 'Játék'],
            ['pluessbaba', 'Plüssbaba'],
            ['gyerekszoba', 'Gyerekszoba'],
            ['diszparna', 'Díszpárna'],
            ['fajatek', 'Fajáték'],
            ['kisboroend', 'Kisbőrönd'],
            ['leggoemb', 'Léggömb'],
            ['ujjbab', 'Ujjbáb'],
            ['egyeb-termekek', 'Egyéb termékek'],
            ['karian-halle-atkozottak-sorozat', 'Átkozottak sorozat'],
            ['tarsadalom', 'Társadalom'],
            ['friss-megjeleneseink', 'Friss megjelenéseink'],
            ['gyermek-es-szulo', 'Gyermek és szülő'],
            ['Tarsosjatakok', 'Társasjátékok'],
            ['irodalom', 'Irodalom'],
            ['a-jatakszer-sorozat', 'A játékszer sorozat '],
            ['kreativ-konyveink', 'Kreatív könyveink'],
            ['kek-macska-es-varom-a-parom', 'Friss megjelenés'],
            ['anyanak', '*Anyának*'],
            ['apanak', '*Apának*'],
            ['gyermekeknek', '*Gyermekeknek*'],
            ['testvernek', '*Testvérnek*'],
            ['nagyszuloknek', '*Nagyszülőknek*'],
            ['tokeletes-karacsony', '*Tökéletes karácsony*'],
            ['ne-tord-a-fejed', '*Ne törd a fejed*'],
            ['terkepek', 'térképek'],
            ['beleolvasok', 'Beleolvasok'],
            ['borsa-brown-tarsasjatekok', 'Borsa Brown társasjátékok'],
            ['2018-sikerkonyvei', '*2018 sikerkönyvei*'],
            ['valentin-nap', '*Valentin nap*'],
            ['tripla-konyv', '*Tripla könyv*'],
            ['csabito-sorozat', 'Csábító sorozat'],
            ['olvass-anne-l-greenel', 'Olvass Anne L. Greenel'],
            ['bohem-rapszodia', 'Bohém rapszódia'],
            ['menedzsment', 'Menedzsment'],
            ['gyalazat-sorozat', 'Gyalázat sorozat'],
            ['e-konyv-beleolvaso', 'E-könyv beleolvasó'],
            ['mr-mount-sorozat', 'Mr. Mount bűnös élete sorozat'],
            ['anyak-es-gyerekek-honapja', '*Anyák és gyerekek hónapja*'],
            ['konyvek-anyaknak', '*Könyvek Anyáknak*'],
            ['konyvek-gyerekeknek', '*Könyvek gyerekeknek*'],
            ['humoros', 'Humoros'],
            ['libri-irodalmi-dij-nyertesek', 'Libri irodalmi díj nyertesek'],
            ['ugri-a-kis-szurke-nyul', '"Ugri, a kis szürke nyúl"'],
            ['a-macko-es-a-zongora-mesek', 'A mackó és a zongora mesék'],
            ['kutyuk-helyett', 'Kütyük helyett'],
            ['kedvenc-klasszikus-meseim', 'Kedvenc Klasszikus meséim'],
            ['babos-mesek', 'Bábos mesék'],
            ['tronok-harca-vilag', 'Trónok harca világ'],
            ['szerelem-vihara', 'Szerelem vihara'],
            ['gyerekkonyvek', 'Gyerekkönyvek'],
            ['dvd-filmek', 'DVD Filmek'],
            ['onfejleszto-konyvek', 'Önfejlesztő könyvek'],
            ['kalandregenyek', 'Kalandregények'],
            ['hobby-szabadido', '"Hobby, szabadidő"'],
            ['napjaink-bulvar', '"Napjaink, bulvár"'],
            ['muveszet-epiteszet', '"Művészet, építészet"'],
            ['muveszettortenet', 'Művészettörténet'],
            ['mese-dvd', 'Mese DVD'],
            ['animacios', 'Animációs'],
            ['idegen-nyelvu', 'Idegen nyelvű'],
            ['uzleti', 'Üzleti'],
            ['kert-haz-otthon', '"Kert, ház, otthon"'],
            ['tortenelmi-konyvek', 'Történelmi könyvek'],
            ['paranormalis', 'Paranormális'],
            ['blu-ray-filmek', 'Blu-ray filmek'],
            ['vigjatek', 'Vígjáték'],
            ['bakelit', 'Bakelit'],
            ['magyar-emlekek', 'Magyar emlékek'],
            ['vers', 'Vers'],
            ['Ambrozy-baro-esetei', 'Ambrózy báró esetei'],
            ['365-mese', '365 mese'],
            ['matricas-konyv', 'Matricás könyv'],
            ['aforizmak', 'Aforizmák'],
            ['albumok', 'Albumok'],
        ];

        foreach ($seedcategories as $slug => $sc) {
            DB::table('subcategory')->insert([
                'title' => $sc[1],
                'slug' => $sc[0],
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
