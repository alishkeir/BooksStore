<?php

namespace Alomgyar\Orders;

use Alomgyar\Countries\Country;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\Shops\Shop;
use App\Exports\OrderExport;
use App\Helpers\HungarianZipMap;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;

    protected $listeners = ['setFilter', 'selectAll'];

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'orders.created_at';

    public $sortAsc = false;

    public $filters = [
        'shop' => false,
        'subcategory' => false,
        'cart_price' => false,
        'only_book' => false,
        'only_ebook' => false,
        'active' => 1,
        'shipping_method' => false,
        'payment_method' => false,
        'payment_status' => false,
        'status' => false,
        'is_shop' => false,
        'is_webshop' => false,
        'only_selection' => false,
        'only_current' => true,
        'from' => false,
        'to' => false,
    ];

    public $data;

    public $shipping = false;

    public $payment = false;

    public $shops = false;

    public $countries = false;

    public $selection = [];

    public $allSelected = false;

    public $barCodeInput = '';

    public $selectionStatuses = [];

    public $statusName = [
        'Piszkozat', 'Megrendelve', 'Feldolgozás alatt', 'Összekészítve', 'Szállítás alatt', 'Átvehető', 'Sikeres',
        'Visszaküldve', 'Törölt',
    ];

    protected $sprinterSelect = [
        'feladas_helye' => 'Telephely',
        'kezbesites_helye' => 'Egyedi cím',
        'zip_code' => '', //zip_code
        'city' => '', //city
        'address' => '', //address
        'address2' => '',
        'name' => '', //name
        'phone' => '', //phone
        'email' => '', //email
        'customer_id' => '',
        'szallitasi_ido' => '2 munkanapos',
        'csomagok_darabszama' => '1',
        'szelesseg' => '',
        'magassag' => '',
        'hosszusag' => '',
        'tomeg' => '',
        'comment' => '', //megjegyzes_a_futarnak
        'order_number' => '', //order_number
        'termek_szamlajanak_azonositoja' => '',
        'csomag_tartalma' => '',
        'utanvet_erteke' => '', //utanvet erteke
        'utanvet_devizanem' => 'HUF',
        'total_amount' => '', //csomag erteke
        'csomag_ertek_devizanem' => 'HUF',
        'okmanyvisszaforgatast_kerek' => 'Nem',
        'csomagcseret_kerek' => '',
        'csere_csomagok_darabszama' => '',
        'csere_szelesseg' => '',
        'csere_magassag' => '',
        'csere_hosszusag' => '',
        'csere_tomeg' => '',
        'csere_megjegyzes_futarnak' => '',
        'csere_megrendeles_kodja' => '',
        'csere_szamla_azonosito' => '',
        'csere_tartalma' => '',
        'csere_utanvet' => '',
        'csere_utanvet_devizanem' => '',
        'csere_erteke' => '',
        'csere_ertek_devizaneme' => '',
        'orszag' => '', //orszag
    ];

    protected $dpdSelect = [
        'szolgaltatas_tipus_dpd' => 'D-B2C-COD',
        'tomeg' => '',
        'total_amount' => '',
        'cod_ref' => '',
        'termek_szamlajanak_azonositoja' => '',
        'address_ref' => '',
        'name' => '', //name
        'contact' => '',
        'address' => '', //address
        'address2' => '',
        'orszag_dpd' => 'H', //orszag
        'zip_code' => '', //zip_code
        'city' => '', //city
        'phone' => '', //phone
        'fax' => '', //fax
        'email' => '', //email
        'comment' => '',
        'telefonszam_interaktiv_ertesiteshez' => '',
        'szemelyazonossag_ellenorzeshez_nev' => '',
        'szemelyazonossag_ellenorzeshez_azonositoszam' => '',
        'azonos_cimzettnek_szolo_kuldemenyek_darabszama' => '',
        'predict' => '',
        'delta_szerviz_szolgaltatas' => '',
        'felado_nyomtatja_a_cimket' => '',
        'felado_adatainak_megjelenitese_a_cimken' => '',
        'aznapi_delta_csomagfelvetel' => '',
        'kert_csomagfelvetel_datuma' => '',
        'felveteli_nev' => '',
        'felveteli_utca' => '',
        'felveteli_varos' => '',
        'felveteli_iranyitoszama' => '',
        'felveteli_cim_orszagkodja' => '',
        'felveteli_cimhez_tartozo_telefonszam' => '',
        'felveteli_cimhez_tartozo_email' => '',
        'felveteli_cimhez_tartozo_kontakt' => '',
        'felvetelhez_tartozo_megjegyzes' => '',
        'teteles_atadas' => '',
        'cod_osszeg_szetosztas_tipusa' => '',
        'csomagpont_azonosito' => '',
        'felulbiztositas_osszege' => '',
        'felulbiztositas_penznem' => '',
        'felulbiztositott_csomag_tartalom' => '',
    ];

    protected $sprinterBoxSelect = [
        'feladasi_boltazonosito' => 'Telephely',
        'kezbesitesi_boltazonosito' => 'Egyedi cím',
        'name' => '', //name
        'phone' => '', //phone
        'email' => '', //email
        'ugyfelkod' => '',
        'zip_code' => '', //zip_code
        'address' => '', //address
        'city' => '', //city
        'address1' => '',
        'address2' => '',
        'meretbesorolas' => '',
        'order_number' => '', //order_number
        'termek_szamlajanak_azonositoja' => '',
        'utanvet_erteke' => '', //utanvet erteke
        'utanvet_devizanem' => 'HUF',
        'total_amount' => '', //csomag erteke
        'csomag_ertek_devizanem' => 'HUF',
    ];

    protected $easyBoxSelect = [
        'feladasi_boltazonosito' => 'Telephely',
        'kezbesitesi_boltazonosito' => 'Egyedi cím',
        'name' => '', //name
        'phone' => '', //phone
        'email' => '', //email
        'ugyfelkod' => '',
        'zip_code' => '', //zip_code
        'address' => '', //address
        'city' => '', //city
        'address1' => '',
        'address2' => '',
        'meretbesorolas' => '',
        'order_number' => '', //order_number
        'termek_szamlajanak_azonositoja' => '',
        'utanvet_erteke' => '', //utanvet erteke
        'utanvet_devizanem' => 'HUF',
        'total_amount' => '', //csomag erteke
        'csomag_ertek_devizanem' => 'HUF',
    ];

    protected $headingsPickPack = [
        '-Feladási boltazonosító',
        '*Kézbesítési boltazonosító',
        '*Címzett neve',
        '*Címzett telefonszáma',
        'Címzett e-mail címe',
        '=Ügyfélkód',
        '=Címzett cím Ir.száma',
        '=Címzett Helység név',
        '=Címzett utca',
        'Címzett hsz., em.',
        '*Méretbesorolás',
        '=Megrendelés kódja',
        'Termék számlájának azonosítója',
        '=Utánvét értéke',
        'Utánvét devizanem',
        '*Csomag értéke',
        '=Csomag értéke devizanem',
    ];

    protected $headingsSprinter = [
        '*Feladás helye',
        '*Kézbesítés helye',
        'Feladási / Kézbesítési cím Ir.száma',
        'Feladási / Kézbesítési cím Helység név',
        'Feladási / Kézbesítési cím utca',
        'Feladási / Kézbesítési cím hsz., em., ajtó',
        'Címzett neve',
        'Címzett telefonszáma (pl. +36301234567)',
        'Címzett email címe',
        'Ügyfélkód',
        'Szállítási idő',
        'Csomagok darabszáma',
        'Szélesség (cm)',
        'Magasság (cm)',
        'Hosszúság (cm)',
        'Tömeg (kg)',
        'Megjegyzés a futárnak',
        'Megrendelés kódja',
        'Termék számlájának azonosítója',
        'Csomag tartalma',
        'Utánvét értéke',
        'Utánvét devizaneme (pl. HUF)',
        'Csomag értéke',
        'Csomag értéke devizaneme (pl. HUF)',
        'Okmányvisszaforgatást kérek',
        'Csomagcserét kérek',
        'Csere csomagok darabszáma',
        'Szélesség (cm)',
        'Magasság (cm)',
        'Hosszúság (cm)',
        'Tömeg (kg)',
        'Megjegyzés a futárnak',
        'Megrendelés kódja',
        'Termék számlájának azonosítója',
        'Csomag tartalma',
        'Utánvét értéke',
        'Utánvét devizaneme (pl. HUF)',
        'Csomag értéke',
        'Csomag értéke devizaneme (pl. HUF)',
        'Feladási / Kézbesítési cím Országkód',
    ];

    protected $headingsSprinterBox = [
        'Feladási boltazonosító',
        'Kézbesítési boltazonosító',
        'Címzett neve',
        'Címzett telefonszáma (pl. +36301234567)',
        'Címzett email címe',
        'Ügyfélkód',
        'Címzett cím Ir.száma',
        'Címzett cím',
        'Helység név',
        'Címzett cím utca',
        'Címzett cím hsz., em., ajtó',
        'Méretbesorolás',
        'Megrendelés kódja',
        'Termék számlájának azonosítója',
        'Utánvét értéke',
        'Utánvét devizaneme',
        'Csomag értéke',
        'Csomag értéke devizaneme',
    ];

    protected $headingsDpd = [
        'Szoláltatás típus',
        'Súly', 'COD összeg',
        'COD referencia',
        'Referencia',
        'Cím referencia',
        'Címzett neve',
        'Kontakt',
        'Utca1',
        'Utca2',
        'Ország',
        'Irányítószám',
        'Város',
        'Telefonszám',
        'Fax',
        'E-mail',
        'Megjegyzés',
        'Telefonszám interaktív értesítéshez',
        'Személyazonosság ellenőrzéshez név',
        'Személyazonosság ellenőrzéshez azonosítószám',
        'Azonos címzettnek szóló küldemények darabszáma',
        'Predict',
        'Delta szerviz szolgáltatás',
        'Feladó nyomtatja a címkét',
        'Feladó adatainak megjelenítése a címkén',
        'Aznapi delta csomagfelvétel',
        'Kért csomagfelvétel dátuma',
        'Felvételi név',
        'Felvételi utca',
        'Felvételi város',
        'Felvételi irányítószáma',
        'Felvételi cím órszágkódja',
        'Felvételi címhez tartozó telefonszám',
        'Felvételi címhez tartozó e-mail cím',
        'Felvételi címhez tartozó kontakt',
        'Felvételhez tartozó megjegyzés',
        'Tételes átadás',
        'COD összeg szétosztás típusa',
        'Csomagpont azonosító (PUDO ID)',
        'Felülbiztosítás összege',
        'Felülbiztosítás pénznem',
        'Felülbiztosított csomag tartalom',
    ];

    protected $headingsPacketa = [
        'Reversed',
        'Order number',
        'Name',
        'Surname',
        'Company',
        'E-mail',
        'Phone',
        'COD',
        'Currency',
        'Value',
        'Weight',
        'Pickup point or carrier',
        'Sender label',
    ];

    protected $packetaSelect = [
        'reversed' => '',
        'order_number' => '',
        'last_name' => '',
        'first_name' => '',
        'company' => '',
        'email' => '',
        'phone' => '',
        'utanvet_round' => '',
        'currency' => 'HUF',
        'total_amount' => '',
        'tomeg' => '',
        'provider_id' => '',
        'sender_label' => 'publishandmore',
    ];

    protected $sameDaySelect = [
        'jogi_forma' => '',
        'name' => '',
        'name2' => '',
        'address' => '',
        'city' => '',
        'megye' => '',
        'phone' => '',
        'tomeg' => '',
        'csomagokszama' => '1', //csomagokszama
        //--//--//--//--//--//--//--
        'utanvet_erteke_sameday' => '',
        'comment' => '', //
        //'termek_szamlajanak_azonositoja'                  => '',
        'order_number' => '', //egyedi_referencia
        'referrencia' => '', //
        //--//--//--//--//--//--//--
        'total_amount' => '', //biztositott_ertek
        'tovabbi_szolg' => '', //
        'harmadik_nev' => '', //
        'harmadik_cim' => '',
        'harmadik-helyseg' => '',
        'harmadik-megye' => '',
        'harmadik-telefon' => '',
        'zip_code' => '',
        'harmadik-iranyitoszam' => '',
    ];

    protected $headingsSameDay = [
        'Jogi forma',
        'Címzett',
        'Átvevő neve',
        'Szállítási cím',
        'Helység',
        'Megye',
        'Telefonszám',
        'Súly',
        'Küldemények száma',
        //--//--//--//--//--//--//--
        'Utánvét',
        'Megjegyzés',
        //--//--//--//--//--//--//--
        'Egyedi hivatkozás',
        'Hivatkozás',
        //
        'Biztosított érték',
        'További szolgáltatások',
        'Átadó neve',
        'Átadó címe',
        'Átadási helység',
        'Átadási megye',
        'Átadó telefonszáma',
        'Irányítószám',
        'Átadási helység irányítószáma',
    ];

    protected $headings = [
        'MEGRENDELÉS AZONOSÍTÓ',
        'NÉV',
        'MEGRENDELÉS KELTE',
        'FIZETÉSI MÓD',
        'SZÁLLÍTÁSI MÓD',
        'RENDELÉS ÁLLAPOTA',
    ];

    protected string $courier;

    public function mount()
    {
        $this->shipping = ShippingMethod::all();
        $this->payment = PaymentMethod::all();
        $this->shops = Shop::all();
        $this->countries = Country::all();
    }

    public function getModelPropery()
    {
        return Order::latest()->paginate(25);
    }

    public function render()
    {
        $data = request()->all();
        foreach ($data ?? [] as $filter => $value) {
            if ($this->filters[$filter] ?? false) {
                $this->filters[$filter] = $value == 'true' ? true : $value;
            }
        }

        if ($this->barCodeInput ?? false) {
            $orderToAdd = Order::where('order_number', $this->barCodeInput)->first();
            if ($orderToAdd) {
                $this->selection[$orderToAdd->id] = $orderToAdd->status;
                $this->filters['only_selection'] = true;
                $this->filters['only_current'] = false;
                $this->barCodeInput = '';
            }
        }

        $this->selectionStatuses = [];
        foreach ($this->selection as $id => $status) {
            if ($status) {
                $this->selectionStatuses[$status] = ($this->selectionStatuses[$status] ?? false) ? $this->selectionStatuses[$status] + 1 : 1;
            } else {
                unset($this->selection[$id]);
            }
        }

        $model = $this->query();
        $model->leftJoin('addresses', function ($join) {
            $join->on('orders.id', '=', 'role_id')->where('addresses.type', 'billing')->where(
                'addresses.role',
                'order'
            );
        });
        $term = trim($this->s);
        if ($term != '') {
            $terms = explode(' ', $term);
            foreach ($terms as $term) {
                $model->search($term);
                $model->orWhere('addresses.first_name', 'like', '%'.$term.'%');
                $model->orWhere('addresses.last_name', 'like', '%'.$term.'%');
            }
        }
        $model->select(
            'orders.store',
            'orders.id',
            'orders.status',
            'orders.payment_status',
            'orders.order_number',
            'orders.total_amount',
            'orders.total_quantity',
            'orders.shipping_method_id',
            'orders.shipping_fee',
            'orders.payment_fee',
            'orders.payment_method_id',
            'orders.created_at',
            // 'addresses.city',
            // 'addresses.first_name',
            // 'addresses.last_name',
            'orders.attachments',
            'orders.invoice_url'
        );
        $model = $model
        ->with('paymentMethod', 'shippingMethod', 'billingAddress')
        ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('orders::components.listcomponent', ['model' => $model]);
    }

    protected function query()
    {
        $term = trim($this->s);
        $model = Order::query();
        if ($term != '') {
            $model->search($term);
        }

        $model->where('orders.status', '>', 0);

        if ($this->filters['payment_method'] ?? false) {
            $model->where('payment_method_id', $this->filters['payment_method']);
        }
        if ($this->filters['shipping_method'] ?? false) {
            $model->where('shipping_method_id', $this->filters['shipping_method']);
        }
        if ($this->filters['status'] ?? false) {
            $model->where('orders.status', $this->filters['status']);
        }
        if ($this->filters['payment_status'] ?? false) {
            if ($this->filters['payment_status'] == Order::STATUS_PAYMENT_PAID) {
                $model->where('payment_status', $this->filters['payment_status']);
            } else {
                $model->where('payment_status', '!=', Order::STATUS_PAYMENT_PAID);
            }
        }
        if ($this->filters['is_shop'] ?? false) {
            $model->where('orders.store', 3);
        }
        if ($this->filters['is_webshop'] ?? false) {
            $model->where('orders.store', '<', 3);
        }
        if ($this->filters['delivery_ok'] ?? false) {
            $model->where('orders.status', 1);
        }
        if ($this->filters['only_current'] ?? false) {
            $model->where('orders.updated_at', '>', Carbon::now()->subMonths(2));
        }
        if ($this->filters['from'] ?? false) {
            $model->where('orders.created_at', '>', $this->filters['from']);
        }
        if ($this->filters['to'] ?? false) {
            $model->where('orders.created_at', '<', $this->filters['to']);
        }
        if ($this->filters['shop'] ?? false) {
            $model->where('orders.shipping_data', 'LIKE', '%{"selected_shop": {"id": '.$this->filters['shop'].'%');
        }

        if ($this->filters['only_selection'] ?? false) {
            $selection = [];
            foreach ($this->selection as $id => $sel) {
                array_push($selection, $id);
            }
            $model = $model->whereIn('orders.id', $selection);
            $selection = [];
        }

        return $model;
    }

    public function selectAll()
    {
        $selectionOld = $this->selection;
        $this->selection = [];
        $model = $this->query()->select('orders.id', 'orders.status');
        $model = $model->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->limit(5000)->get();
        foreach ($model as $toSelect) {
            if (! isset($selectionOld[$toSelect->id])) {
                $this->selection[$toSelect->id] = $toSelect->status;
            } else {
                $this->selection[$toSelect->id] = false;
            }
        }
    }

    public function setOrderStatuses($from, $to)
    {
        foreach ($this->selection as $id => $status) {
            if ($status && $status == $from) {
                $targetOrder = Order::find($id);
                if ($to != Order::STATUS_DELETED) {
                    if ($targetOrder->setStatus($to)) {
                    }
                } else {
                    $targetOrder->status = Order::STATUS_DELETED;
                    $targetOrder->save();
                }

                $this->selection[$id] = $to;
            }
        }
    }

    public function setOrderPaid($selectedStatus)
    {
        $toPaidIds = [];
        foreach ($this->selection as $id => $status) {
            if ($status && $status == $selectedStatus) {
                array_push($toPaidIds, $id);
            }
        }

        $sql = '
            UPDATE orders
            SET payment_status=3
            WHERE id IN ('.implode(', ', $toPaidIds).');
            ';
        DB::statement($sql);
    }

    public function setFilter($filter)
    {
        foreach ($filter as $key => $value) {
            if ($key == 'payment_method') {
                $this->filters['payment_method'] = $value;
            }
            if ($key == 'shipping_method') {
                $this->filters['shipping_method'] = $value;
            }
            if ($key == 'shop') {
                $this->filters['shop'] = $value;
            }
        }
    }

    public function sortBy($column)
    {
        if ($this->sortField === $column) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $column;
    }

    public function downloadShippingExcel($courier, $type)
    {
        // IF HOME = HOME
        // IF DPD = DPD && HOME
        // IF SAMEDAY = SAMEDAY && HOME

        $this->courier = $courier;
        $this->type = $type;
        $rows = $this->makeShippingExcel();
        $heading = $this->getHeadings();
        $format = 'xls';
        if ($this->courier === 'packeta') {
            $format = 'csv';
            $heading = ['version 6'];
            array_unshift($rows, $this->getHeadings());
        }

        if ($rows ?? false) {
            return (new OrderExport($rows, $heading))->download('cimke-'.$this->courier.'-'.date('Y-m-d').'.'.$format);
        } else {
            $this->dispatchBrowserEvent('toast-message', ['message' => 'Nincs a feltételeknek megfelelő rendelés!', 'type' => 'warning']);

            return false;
        }
    }

    public function downloadExcel()
    {
        $selection = [];
        foreach ($this->selection as $id => $sel) {
            $selection[] = $id;
        }

        $model = Order::select('orders.*', 'addresses_shipping.last_name', 'addresses_shipping.first_name');
        $model->whereIn('orders.id', $selection);
        $model->leftJoin('addresses as addresses_shipping', function ($join) {
            $join->on('orders.id', '=', 'addresses_shipping.role_id')->where(
                'addresses_shipping.type',
                'shipping'
            )->where('addresses_shipping.role', 'order');
        });
        $model = $model->latest()->get();
        $docCount = 0;
        foreach ($model as $order) {
            $orderAttachmentCount = count(array_unique($order->attachments ?? []));
            if ($orderAttachmentCount > $docCount) {
                $docCount = $orderAttachmentCount;
            }
        }
        $rows = $this->makeExcel($model, $docCount);
        $headings = $this->headings;
        for ($i = 0; $i < $docCount; $i++) {
            $headings[] = 'SZÁMLA/ NYUGTA SORSZÁMA '.($i + 1).'.';
        }
        if ($rows ?? false) {
            return (new OrderExport($rows, $headings))->download('megrendelesek-'.date('Y-m-d').'.xls');
        } else {
            $this->dispatchBrowserEvent('toast-message', ['message' => 'Nincs a feltételeknek megfelelő rendelés!', 'type' => 'warning']);

            return false;
        }
    }

    protected function makeExcel($model, $docCount)
    {
        $select = [
            'order_number',
            'name',
            'order_date',
            'payment_method',
            'shipping_method',
            'order_status',
        ];

        foreach ($model as $row) {
            foreach ($select as $column) {
                $rows[$row->order_number][$column] = match ($column) {
                    'order_number' => $row->order_number,
                    'name' => $row->last_name.' '.$row->first_name,
                    'order_date' => $row->created_at->format('d/m/Y'),
                    'payment_method' => $row->paymentMethod->name,
                    'shipping_method' => $row->shippingMethod->name,
                    'order_status' => $this->statusName[$row->status] ?? 'N/A',
                };
            }
            $attachments = array_unique($row->attachments ?? []);
            for ($i = 0; $i < $docCount; $i++) {
                $rows[$row->order_number]['doc_'.$i] = $attachments[$i] ?? '';
            }
        }

        return $rows;
    }

    // MANUAL TEST FOR DPD & SAMEDAY
    // MANUAL TEST FOR DPD & SAMEDAY
    // MANUAL TEST FOR DPD & SAMEDAY
    // MANUAL TEST FOR DPD & SAMEDAY
    // MANUAL TEST FOR DPD & SAMEDAY
    // MANUAL TEST FOR DPD & SAMEDAY
    // MANUAL TEST FOR DPD & SAMEDAY
    // MANUAL TEST FOR DPD & SAMEDAY

    protected function makeShippingExcel()
    {
        // IF HOME = HOME
        // IF DPD = DPD && HOME
        // IF SAMEDAY = SAMEDAY && HOME

        // METHOD ID == TYPE
        // METHOD_ID WHEREIN()
        //$shipping  = $this->shipping->where('method_id', $this->type)->first();

        $searchedShippingMethodIds = [];
        if ($this->type == ShippingMethod::HOME) {
            $searchedShippingMethodIds = $this->shipping->where('method_id', ShippingMethod::HOME)->pluck('id');
        } elseif ($this->type == ShippingMethod::DPD) {
            $searchedShippingMethodIds = $this->shipping->whereIn('method_id', [ShippingMethod::HOME, ShippingMethod::DPD])->pluck('id');
        } elseif ($this->type == ShippingMethod::SAMEDAY) {
            $searchedShippingMethodIds = $this->shipping->whereIn('method_id', [ShippingMethod::HOME, ShippingMethod::SAMEDAY])->pluck('id');
        } else {
            $searchedShippingMethodIds[] = $this->shipping->where('method_id', $this->type)->first()->id;
        }

        $cod = $this->payment->where('method_id', 'cash_on_delivery')->first();
        $selection = [];
        foreach ($this->selection as $id => $sel) {
            $selection[] = $id;
        }

        $model = Order::select([
            'orders.*',
            'addresses_shipping.*',
            'customers.phone',
            'addresses_billing.entity_type AS billing_entity_type',
            DB::raw('IF(addresses_shipping.last_name is not null,addresses_shipping.last_name,IF(addresses_billing.last_name is not null,addresses_billing.last_name,customers.lastname)) as last_name'),
            DB::raw('IF(addresses_shipping.first_name is not null,addresses_shipping.first_name,IF(addresses_billing.first_name is not null,addresses_billing.first_name,customers.firstname)) as first_name'),
        ]);
        $model->whereIn('orders.id', $selection);
        $model->where('orders.store', '<', 3);
        $model->whereIn('orders.shipping_method_id', $searchedShippingMethodIds);
        $model->where('orders.status', '<', 5);

        if ($this->type === 'box') {
            if ($this->courier === 'easybox') {
                $model->where('orders.boxprovider', 'easybox');
            } elseif ($this->courier === 'packeta') {
                $model->where('orders.boxprovider', 'packeta');
            } else {
                $model->where('orders.boxprovider', 'posta');
            }
        }

        $model->leftJoin('addresses as addresses_shipping', function ($join) {
            $join->on('orders.id', '=', 'addresses_shipping.role_id')->where(
                'addresses_shipping.type',
                'shipping'
            )->where('addresses_shipping.role', 'order');
        });
        $model->leftJoin('addresses as addresses_billing', function ($join) {
            $join->on('orders.id', '=', 'addresses_billing.role_id')->where(
                'addresses_billing.type',
                'billing'
            )->where('addresses_billing.role', 'order');
        });

        $model->join('customers', ['orders.customer_id' => 'customers.id']);

        $model = $model
            ->distinct()
            ->orderBy('last_name')
            ->get();

        ray($model)->red();

        //$select = $this->courier === 'sprinter' ? ($this->type === 'home' ? $this->sprinterSelect : $this->sprinterBoxSelect) : $this->dpdSelect;
        if ($this->courier === 'sprinter') {
            $select = $this->type === 'home' ? $this->sprinterSelect : $this->sprinterBoxSelect;
        } elseif ($this->courier === 'sameday') {
            $select = $this->sameDaySelect;
        } elseif ($this->courier === 'packeta') {
            $select = $this->packetaSelect;
        } elseif ($this->courier === 'easybox') {
            $select = $this->easyBoxSelect;
        } else {
            $select = $this->dpdSelect;
        }
        foreach ($model as $row) {
            if ($row->billing_entity_type == 1) {
                $entityType = 'MSZ';
            } elseif ($row->billing_entity_type == 2) {
                $entityType = 'JSZ';
            } else {
                $entityType = '';
            }
            // if ($this->courier === 'sameday') {
            //     $orderNumber = false;
            //     if (!empty($row->attachments)) {
            //         foreach ($row->attachments as $attachment) {
            //             if ($orderNumber === false && $attachment == $row->invoice_url) {
            //                 $orderNumber = $attachment;
            //             }
            //         }
            //     }
            // } else {
            //     $orderNumber = $row->order_number;
            // }

            $zipCode = $row->shipping_data[$this->type]['user_selected_address']['zip_code'] ?? $row->zip_code ?? '';

            // ULTRA FAIL SAFE
            $dpdCountryCode = $this->countries->where('id', $row->country_id)->first()?->code !== 'HU'
                ? $this->countries->where('id', $row->country_id)->first()?->code ?? 'H'
                : 'H';

            foreach ($select as $column => $fallback) {
                $rows[$row->order_number][$column] = match ($column) {
                    'szolgaltatas_tipus_dpd' => $row->payment_status == Order::STATUS_PAYMENT_PAID ? 'D' : 'COD',
                    'kezbesitesi_boltazonosito' => $row->shipping_details->id ?? $row->shipping_details->shopCode ?? $row->shipping_details->place_id ?? $row->shipping_details->provider_id ?? '',
                    'name' => $this->type === 'home' ? $row->last_name.' '.$row->first_name : $this->getFullNameFromShippingData($row),
                    'name2' => $this->type === 'home' ? $row->last_name.' '.$row->first_name : $this->getFullNameFromShippingData($row),
                    'first_name' => $row->first_name ?? ($row->shipping_data[$this->type]['user_selected_address']['first_name'] ?? ''),
                    'last_name' => $row->last_name ?? ($row->shipping_data[$this->type]['user_selected_address']['last_name'] ?? ''),
                    'phone' => $row->phone ?? $row->address_phone ?? $row->shipping_details->phone ?? '',
                    'utanvet_erteke' => $row->payment_method_id === $cod->id ? $cod->{'fee_'.$row->store} : '',
                    'utanvet' => $row->payment_method_id === $cod->id ? $row->total_amount : 0,
                    'utanvet_erteke_sameday' => $row->payment_method_id === $cod->id ? (round($row->total_amount / 5, 0) * 5) : 0,
                    'utanvet_round' => $row->payment_method_id === $cod->id ? (round($row->total_amount / 5, 0) * 5) : 0,
                    'provider_id' => $row->shipping_details->provider_id ?? ($row->shipping_data[$this->type]['user_selected_address']['provider_id'] ?? ''),
                    'orszag' => $this->countries->where('id', $row->country_id)->first()->code ?? 'HU',
                    'orszag_dpd' => $dpdCountryCode,
                    'tomeg' => $row->total_quantity > 10 ? '2' : '1',
                    'termek_szamlajanak_azonositoja' => $row->invoice_url ?? 'N/A',
                    'megye' => HungarianZipMap::getZipMap()[$zipCode] ?? '',
                    'jogi_forma' => $entityType,
                    //'order_number' => $orderNumber, //Megrendelés szám - kiemelve a SameDay miatt
                    'order_number' => $row->invoice_url ?? $row->order_number ?? 'N/A',
                    'comment' => $row->comment ?? $row->shipping_data[$this->type]['user_selected_address']['comment'] ?? '',
                    default => $row->{$column} ?? ($fallback == '' ? '' : $fallback),
                };

                if ($this->type == ShippingMethod::BOX) {
                    $rows[$row->order_number][$column] = match ($column) {
                        'zip_code' => $row->shipping_details->zip ?? '',
                        'address' => $row->shipping_details->address ?? '',
                        'city' => $row->shipping_details->county ?? '',
                        default => $rows[$row->order_number][$column] ?? (
                            $row->{$column} ?? ($fallback == '' ? '' : $fallback)),
                    };
                }

                if ($this->type == ShippingMethod::DPD || $this->type == ShippingMethod::SAMEDAY) {
                    switch ($column) {
                        case 'zip_code':
                            $rows[$row->order_number]['zip_code'] = $zipCode;
                            break;
                        case 'address':
                            $rows[$row->order_number]['address'] = $row->shipping_data[$this->type]['user_selected_address']['address'] ?? $row->address ?? '';
                            break;
                        case 'city':
                            $rows[$row->order_number]['city'] = $row->shipping_data[$this->type]['user_selected_address']['city'] ?? $row->city ?? '';
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        return $rows ?? false;
    }

    protected function getHeadings()
    {
        if ($this->courier === 'sprinter') {
            return $this->type === 'home' ? $this->headingsSprinter : $this->headingsSprinterBox;
        } elseif ($this->courier === 'sameday') {
            return $this->headingsSameDay;
        } elseif ($this->courier === 'packeta') {
            return $this->headingsPacketa;
        } else {
            return $this->headingsDpd;
        }

        //return $this->courier === 'sprinter' ? ($this->type === 'home' ? $this->headingsSprinter : $this->headingsSprinterBox) : $this->headingsDpd;
    }

    protected function getFullNameFromShippingData($row)
    {
        $lastName = $row->shipping_data[$this->type]['user_selected_address']['last_name'] ?? $row->last_name;
        $firstName = $row->shipping_data[$this->type]['user_selected_address']['first_name'] ?? $row->first_name;

        return $lastName.' '.$firstName;
    }
}
