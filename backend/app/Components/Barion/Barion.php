<?php

namespace App\Components\Barion;

use Alomgyar\Carts\CartItem;

class Barion
{
    public function create()
    {
        $payment_token = $this->barionPrefix[0].'-'.mt_rand(100000, 999999).$this->cart->id;
        $this->barion = $this->BarionInit();

        $trans = new \PaymentTransactionModel();
        $trans->POSTransactionId = $payment_token;
        $trans->Payee = env('BARION_PAYEE', 'aranytoth.tibor@gmail.com');
        $trans->Total = $this->getTotal();
        $trans->Currency = \Currency::HUF;
        $trans->Comment = '';

        foreach ($this->cart->items as $key => $item) {
            $trans->AddItem($this->BarionItem($item, $payment_token));
        }

        $ppr = $this->BarionPreparePayment($trans, $payment_token);

        $dibookPayment = $this->barion->PreparePayment($ppr);

        if ($dibookPayment->RequestSuccessful) {
            return $dibookPayment->PaymentRedirectUrl;
        } else {
            return $dibookPayment;
        }

        return $this->barion;
    }

    /**
     * Barion API inicializálása
     *
     * @return BarionClient
     */
    private function BarionInit()
    {
        require_once app_path().'/Components/Barion/BarionClient.php';

        $myPosKey = env('BARION_POS_KEY', 'c8dc6fe465e041338703864044a692fc');
        $environment = env('BARION_ENV', 'test') == 'test' ? \BarionEnvironment::Test : \BarionEnvironment::Prod;
        $apiVersion = 2;
        $BC = new \BarionClient($myPosKey, $apiVersion, $environment);

        return $BC;
    }

    private function BarionItem(CartItem $cartItem, string $payment_token)
    {
        $item = new \ItemModel();
        $item->Name = $cartItem->product->title;
        $item->Description = $cartItem->product->title.' vásárlása';
        $item->Quantity = $cartItem->quantity;
        $item->Unit = 'db';
        $item->UnitPrice = round($cartItem->product->price($this->cart->store)->price_list); // TODO aktuális árak implpementálása
        $item->ItemTotal = round($cartItem->product->price($this->cart->store)->price_list) * $cartItem->quantity; // TODO ugyanaz
        //TODO Kedvezményes ár implementálása
        $item->SKU = 'DBK-'.$cartItem->product_id.'-'.$payment_token;

        return $item;
    }

    private function BarionPreparePayment($trans, $payment_token)
    {
        $ppr = new \PreparePaymentRequestModel();
        $ppr->GuestCheckout = true;
        $ppr->PaymentType = \PaymentType::Immediate;
        $ppr->FundingSources = [\FundingSourceType::All];
        $ppr->PaymentRequestId = 'PAYMENT-'.$payment_token;
        $ppr->PayerHint = str_replace(' ', '', 'aranytoth.tibor+barion@gmail.com'); //TODO user email implement
        $ppr->Locale = \UILocale::HU;
        $ppr->OrderNumber = 'DBK-'.$payment_token;
        $ppr->Currency = \Currency::HUF;
        $ppr->RedirectUrl = route('checkout.check', ['store' => $this->cart->store]);
        $ppr->CallbackUrl = route('checkout.callback', ['store' => $this->cart->store]);
        $ppr->AddTransaction($trans);

        return $ppr;
    }

    private function getTotal()
    {
        $total = 0;

        foreach ($this->cart->items as $item) {
            $price = ($item->is_cart_price ? $item->product->price($this->cart->store)->price_cart : $item->product->price($this->cart->store)->price_sale) * $item->quantity;
            $total += round($price);
        }

        return $total;
    }
}
