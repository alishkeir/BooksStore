<?php

namespace App\Services\Email;

use Alomgyar\Carts\Cart;
use Alomgyar\Templates\Email\TemplatedMail;
use Alomgyar\Templates\Entity\TemplatedMailEntity;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Templates;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LostCartEmailService
{
    public function sendMail(Cart $cart)
    {
        $service = new ContentParserService();
        $template = Templates::whereSlug('lost-email')->firstOrFail();

        $subject = $service->parseContent($template->subject, []);
        $body = $service->parseContent($template->description, [
            'PRODUCT_TABLE' => $this->buildProductTable($cart),
            'CUSTOMER_NAME' => $cart->customer->firstname ?? 'Vásárló',
        ]);

        $templatedMail = new TemplatedMailEntity();
        $templatedMail->setSubject($subject);
        $templatedMail->setBody($body);
        $templatedMail->setStoreId($cart->store);

        if (! empty($cart->customer)) {
            Mail::to(str_replace(' ', '', $cart->customer->email))->send(new TemplatedMail($templatedMail));
//            Mail::to($cart->customer->email)->send(new TemplatedMail($templatedMail));
        }

        $cart->reminded_at = Carbon::now();
        $cart->save();
        Log::info('Lost Cart Email sent to cart: '.$cart->id);
    }

    private function buildProductTable(Cart $cart): string
    {
        return view('emails.partial.product-table', ['cart' => $cart])
            ->render();
    }
}
