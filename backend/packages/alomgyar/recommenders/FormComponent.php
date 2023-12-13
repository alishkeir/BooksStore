<?php

namespace Alomgyar\Recommenders;

use Alomgyar\Products\Product;
use Alomgyar\Recommenders\Repository\RecommenderRepository;
use Alomgyar\Templates\Email\TemplatedMail;
use Alomgyar\Templates\Entity\TemplatedMailEntity;
use Alomgyar\Templates\Services\ContentParserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;

class FormComponent extends Component
{
    public string $releaseDate = '';

    public int $originalProductId = 0;

    public int $promotedProductId = 0;

    public string $subject = '';

    public string $messageBody = '';

    public int $customerNum = 0;

    public int $storeId = 0;

    public ?Recommender $recommender;

    public string $toEmail = '';

    protected $rules = [
        'messageBody' => 'required',
        'releaseDate' => 'required',
        'originalProductId' => 'required',
        'promotedProductId' => 'required',
        'subject' => 'required',
    ];

    protected $messages = [
        'releaseDate.required' => 'Mező megadása kötelező!',
        'messageBody' => 'Mező megadása kötelező!',
        'originalProductId' => 'Mező megadása kötelező!',
        'promotedProductId' => 'Mező megadása kötelező!',
        'subject' => 'Mező megadása kötelező!',
    ];

    protected $listeners = [
        'setMessageBody',
    ];

    public function render(): View
    {
        $originalProduct = Product::find($this->originalProductId);
        $promotedProduct = Product::find($this->promotedProductId);

        return view('recommenders::components.form', [
            'originalProduct' => $originalProduct,
            'promotedProduct' => $promotedProduct,
        ]);
    }

    public function submit()
    {
        $this->validate();

        $this->recommender->message_body = $this->messageBody;
        $this->recommender->subject = $this->subject;
        $this->recommender->release_date = $this->releaseDate;
        $this->recommender->original_product_id = $this->originalProductId;
        $this->recommender->promoted_product_id = $this->promotedProductId;
        $this->recommender->store = $this->storeId;

        $this->recommender->save();

        session()->flash('success', 'Ajánlás mentése sikeres volt.');

        return redirect()->route('recommenders.index');
    }

    public function mount()
    {
        if ($this->recommender->id) {
            $this->releaseDate = Carbon::parse($this->recommender->release_date)->format('Y-m-d\TH:i:s');
            $this->originalProductId = $this->recommender->original_product_id;
            $this->promotedProductId = $this->recommender->promoted_product_id;
            $this->subject = $this->recommender->subject;
            $this->messageBody = $this->recommender->message_body;
            $this->storeId = $this->recommender->store;
            $this->customerNum = (new RecommenderRepository())->getCustomerNumByProductId($this->recommender->original_product_id);
        } else {
            $this->messageBody = '<p>Kedves %LAST_NAME%!</p><br /><p>Mivel korábban megvásároltad a(z) %ORIGINAL_BOOK% című könyvet, ezért szeretnénk a figyelmedbe ajánlani a következő alkotást: %PROMOTED_BOOK%</p>';
        }
    }

    public function sendTestEmail(): void
    {
        $validator = \Illuminate\Support\Facades\Validator::make([
            'toEmail' => $this->toEmail,
        ], [
            'toEmail' => 'required|email',
        ], [
            'toEmail.required' => 'Mező megadása kötelező!',
            'toEmail.email' => 'Úgy néz ki a megadott érték nem email formátumú!',
        ]
        );

        $validator->validate();

        $contentParser = new ContentParserService();

        $subject = $contentParser->parseContent($this->subject, $this->parseArray());
        $body = $contentParser->parseContent($this->messageBody, $this->parseArray());

        $templatedMail = new TemplatedMailEntity();
        $templatedMail->setStoreId($this->storeId);
        $templatedMail->setSubject($subject);
        $templatedMail->setBody($body);

        Log::info('Reccomender email teszt to '.$this->toEmail);

        Mail::to($this->toEmail)->send(new TemplatedMail($templatedMail));

        $this->dispatchBrowserEvent('toast-message', "Sikeres teszt email küldés ide: {$this->toEmail}!");
    }

    public function hydrate(): void
    {
        $this->fireRestartJs();
    }

    public function updated(): void
    {
        if ($this->originalProductId) {
            $this->customerNum = (new RecommenderRepository())->getCustomerNumByProductId($this->originalProductId);
        }

        $this->fireRestartJs();
    }

    private function fireRestartJs(): void
    {
        $this->dispatchBrowserEvent('restartJs');
    }

    private function parseArray()
    {
        $originalProduct = Product::find($this->originalProductId);
        $promotedProduct = Product::find($this->promotedProductId);

        $cover = env('BACKEND_URL').'/storage/'.$promotedProduct->cover;

        return [
            'LAST_NAME' => Auth::user()->name,
            'ORIGINAL_BOOK' => $originalProduct->title,
            'PROMOTED_BOOK' => $promotedProduct->title,
            'PROMOTED_BOOK_DESCRIPTION' => $promotedProduct->description,
            //'PROMOTED_BOOK_COVER' => "<img style='width: 100%' src='{$cover}' alt='{$promotedProduct->title}'>",
            'PROMOTED_BOOK_COVER' => "<img style='display:block; margin-left:auto; margin-right:auto; width: 50%;' src='{$cover}' alt='{$promotedProduct->title}'>",
        ];
    }

    public function setMessageBody($content)
    {
        $this->messageBody = $content;
    }

    public function dehydrate()
    {
        $this->dispatchBrowserEvent('restartTinyMCE');
    }
}
