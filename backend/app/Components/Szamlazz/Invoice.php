<?php

namespace App\Components\Szamlazz;

use Alomgyar\Customers\Address;
use App\Order;
use Carbon\Carbon;

/**
 * Számlakészítés
 * Class Invoice
 */
class Invoice
{
    private $afaNum;

    private $afaString;

    private Order $order;

    private $allBooks;

    private $szamlaKey;

    public function __construct($orderId, $allBooks = false)
    {
        require_once app_path().'/Components/Szamlazz/autoload.php';

        $this->afaNum = 5;
        $this->afaString = '5';
        $this->allBooks = $allBooks;
        $this->order = Order::with('orderItems.product', 'country', 'customer', 'billingAddress', 'shippingMethod')->where('payment_token', $orderId)->first();
        $this->szamlaKey = env('SZAMLAZZ_'.$this->order->store, 'qwd4jiyr796mzq9jcbybc67fpsezrzw4imwe32ta4v');
    }

    public function create()
    {
        if (empty($this->order)) {
            return false;
        }

        foreach ($this->order->orderItems as $item) {
            if ($item->product->type == 0) {
                //ha a rendelésben nemcsak ebook van, akkor nem készítünk számlát
                return true;
            }
        }

        $billingAddress = $this->order->billingAddress;
        if (empty($billingAddress)) {
            $billingAddress = Address::where([['role_id', $this->order->customer_id], ['role', 'customer']])->first();
        }

        $agent = \SzamlaAgent\SzamlaAgentAPI::create($this->szamlaKey, true, 0);

        $invoice = new \SzamlaAgent\Document\Invoice\Invoice(\SzamlaAgent\Document\Invoice\Invoice::INVOICE_TYPE_P_INVOICE);
        $header = $invoice->getHeader();
        $header->setOrderNumber($this->order->order_number);
        $header->setPaymentMethod(\SzamlaAgent\Document\Invoice\Invoice::PAYMENT_METHOD_BANKCARD);
        $header->setCurrency(\SzamlaAgent\Currency::CURRENCY_HUF);
        $header->setLanguage(\SzamlaAgent\Language::LANGUAGE_HU);
        $header->setPaid(true);

        // vevő létrehozása
        $buyer = new \SzamlaAgent\Buyer(
            $billingAddress->last_name.' '.$billingAddress->first_name,
            $billingAddress->zip_code,
            $billingAddress->city,
            $billingAddress->address
        );
        $buyer->setCountry($this->order->country->name);
        if (! empty($billingAddress->vat_number)) {
            $buyer->setTaxNumber($billingAddress->vat_number);
        }

        $buyer->setEmail($this->order->customer->email);

        $invoice->setBuyer($buyer);

        foreach ($this->order->orderItems as $item) {
            $netto = round($item->price / (1 + ($this->afaNum / 100)));

            $invoiceItem = new \SzamlaAgent\Item\InvoiceItem($item->product->title, $netto, $item->quantity, 'db', $this->afaString);
            // Tétel nettó értéke
            $invoiceItem->setNetPrice($netto * $item->quantity);
            // Tétel ÁFA értéke
            $invoiceItem->setVatAmount($item->price - $netto);
            // Tétel bruttó értéke
            $invoiceItem->setGrossAmount($item->price * $item->quantity);
            // Tétel hozzáadása a számlához
            $invoice->addItem($invoiceItem);
        }
        /* szállítási költség hozzáadása */
        if ($this->order->shipping_fee > 0) {
            $sNetto = round($this->order->shipping_fee / (1 + $this->afaNum / 100));
            $shippingItem = new \SzamlaAgent\Item\InvoiceItem('Kényelmi költség ('.$this->order->shippingMethod->name.')', $sNetto, 1, 'db', $this->afaString);
            // Tétel nettó értéke
            $shippingItem->setNetPrice($sNetto);
            // Tétel ÁFA értéke
            $shippingItem->setVatAmount($this->order->shipping_fee - $sNetto);
            // Tétel bruttó értéke
            $shippingItem->setGrossAmount($this->order->shipping_fee);
            // Tétel hozzáadása a számlához
            $invoice->addItem($shippingItem);
        }

        $result = $agent->generateInvoice($invoice);

        if ($result->isSuccess()) {
            return true;
        }
    }

    public function createStorno()
    {
        if (empty($this->order)) {
            return false;
        }

        if (empty($this->order->invoice_url)) {
            return false;
        }

        try {
            $agent = \SzamlaAgent\SzamlaAgentAPI::create($this->szamlaKey, true, 0);

            $invoice = new \SzamlaAgent\Document\Invoice\ReverseInvoice(\SzamlaAgent\Document\Invoice\ReverseInvoice::INVOICE_TYPE_P_INVOICE);

            $header = $invoice->getHeader();
            $header->setOrderNumber($this->order->order_number);
            $header->setInvoiceNumber($this->order->invoice_url);
            $header->setIssueDate(Carbon::now()->format('Y-m-d'));
            $header->setFulfillment(Carbon::now()->format('Y-m-d'));
            $header->setInvoiceTemplate(\SzamlaAgent\Document\Invoice\Invoice::INVOICE_TEMPLATE_DEFAULT);

            $seller = new \SzamlaAgent\Seller();
            $seller->setEmailReplyTo('aranytoth.tibor@gmail.com');
            $seller->setEmailSubject('Sztornó számla');
            $seller->setEmailContent('A '.$this->order->invoice_url.' számú rendelésed sztornózva');
            $invoice->setSeller($seller);

            $buyer = new \SzamlaAgent\Buyer();
            if ($this->order->customer->email ?? false) {
                $buyer->setEmail($this->order->customer->email);
            }

            $invoice->setBuyer($buyer);

            $result = $agent->generateReverseInvoice($invoice);
            $attachments = $this->order->attachments;

            if ($result->isSuccess()) {
                $this->order->invoice_url = null;
                $attachments[] = $result->getDocumentNumber();
                $this->order->attachments = $attachments;
                $this->order->save();

                return true;
            } else {
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
