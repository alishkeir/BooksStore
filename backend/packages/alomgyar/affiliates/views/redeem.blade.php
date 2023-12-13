<head>
    <style>
        .font-xl {
            font-size: 17px;
        }
        .font-l {
            font-size: 11px;
        }
        .font-m, td {
            font-size: 9px;
        }
        .text-align-right {
            text-align: right;
        }
        .border-thick-bottom {
            border-bottom: 3px solid rgb(208, 3, 3);
        }
        .border-bottom {
            border-bottom: 2px solid rgb(208, 3, 3);
        }
        .table-data {
            padding: 3px 0px;
            background-color: rgb(217, 213, 213);
        }
        .red {
            color: rgb(220, 16, 16);
        }
        table {
            padding: 5px 0;
        }
        .padding-2 {
            padding: 2px;
        }
        .padding-x-10 {
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
</head>
<body class="font-m">
    <table class="border-thick-bottom">
        <tr>
            <td class="font-xl text-align-right"><b>AFFILIATE JÓVÁÍRÁS IGÉNYLÉS</b></td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="font-l text-align-right">Igénylés azonosítója: <b>{{$pdfName}}</b></td>
        </tr>
    </table>
    <table style="">
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table class="padding-2">
        <tr>
            <td class="font-l"><b>AFFILIATE PARTNER ADATOK</b></td>
        </tr>
        <tr>
            <td>Számlázási név: <b>{{$affiliateData?->name}}</b></td>
        </tr>
        <tr>
            <td>Egyedi affiliate kód: <b>{{$affiliateData?->code}}</b></td>
        </tr>
        <tr>
            <td><b>{{$affiliateData?->country}}</b></td>
        </tr>
        <tr>
            <td><b>{{$affiliateData?->zip}}</b></td>
        </tr>
        <tr>
            <td><b>{{$affiliateData?->city}}</b></td>
        </tr>
        <tr>
            <td><b>{{$affiliateData?->address}}</b></td>
        </tr>
        <tr>
            <td><b>{{$affiliateData?->vat}}</b></td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="text-align-right">Igénylés dátuma: <b>{{$dateOfRedeem}}</b></td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="text-align-right">Elszámolás időszaka: <b>{{$dateOfPreviousRedeem}} - {{$dateOfRedeem}}</b></td>
        </tr>
    </table>
    <table>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table class="border-bottom">
        <tr>
            <th><b>Megnevézes</b></th>
            <th><b>Menny.</b></th>
            <th><b>Egységár</b></th>
            <th><b>Nettó ár</b></th>
        </tr>
    </table>
    <table class="table-data">
        <tr>
            <td>Affiliate értékesítési jutalék</td>
            <td>1 db</td>
            <td>{{$amount}}</td>
            <td>{{$amount}}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td><b>összesen</b></td>
            <td class="text-align-right"><b>{{$amount}}</b></td>
        </tr>
    </table>
    <table class="padding-x-10">
        <tr>
            <td class="text-align-right font-l">összesen:</td>
        </tr>
        <tr>
            <td class="text-align-right font-l red"><b>Nettó {{$amount}} HUF</b></td>
        </tr>
    </table>
    <table class="padding-x-10">
        <tr>
            <td class="font-xl"><b>Az Affiliate jóváírás igénylés PDF</b> alapján szükséges kiállítani a számlát.</td>
        </tr>
    </table>
    <table class="padding-x-10">
        <tr>
            <td class="font-m"><b>Számlázási adataink:</b></td>
        </tr>
        <tr>
            <td class="font-m">Cégnév: Publish and More Kft.</td>
        </tr>
        <tr>
            <td class="font-m">Adószám: 23845338-2-41</td>
        </tr>
        <tr>
            <td class="font-m">Székhely: 1137 Budapest, Pozsonyi út 10. 1/4.</td>
        </tr>
    </table>
    <table class="padding-x-10">
        <tr>
            <td class="font-m">Számla kelte: a kiállítás napja</td>
        </tr>
        <tr>
            <td class="font-m">Teljesítés: a számla kelte +30 nap</td>
        </tr>
        <tr>
            <td class="font-m">Fizetési határidö: a számla kelte +30 nap</td>
        </tr>
    </table>
    <table class="padding-x-10">
        <tr>
            <td class="font-m"><b>E-mail: <a href="mailto:penzugy@alomgyar.hu">penzugy@alomgyar.hu</a></b></td>
        </tr>
        <tr>
            <td class="font-m">Tárgy: alomgyar.hu affiliate jutalék + Affiliate jóváírás <b>igénylés (PDF) azonosítója</b></td>
        </tr>
        <tr>
            <td class="font-m">Iroda (postán ide várjuk a számlákat): 1065 Budapest, Bajcsy-Zsilinszky út 57., A épület 3. emelet</td>
        </tr>
    </table>
    <table class="padding-x-10">
        <tr>
            <td class="font-m">Fontos: Az általunk megjelölt jutalék nettó (áfa mentes) összeg. Amennyiben a számlát kiállító áfakörös, akkor 27% áfával növelt értéken kell kiállítani. Minden esetben javasoljuk, hogy könyvelövel egyeztessen a partner.
                A számlát hibás adattartalommal (például a számla tárgyából hiányzik az elszámolás egyedi kódja) nem tudjuk elfogadni.
                Évente legfeljebb {{$affiliateSettings['redeems_per_year']}} elszámolást tudunk befogadni, amennyiben az adott elszámolás legalább nettó {{$affiliateSettings['minimum_redeem_amount']}}.</td>
        </tr>
    </table>

</body>
