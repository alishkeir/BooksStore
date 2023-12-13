<?php

namespace Alomgyar\Consumption_reports;

use Alomgyar\Orders\Helpers\HTMLBuilder;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class MerchantReportListComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = false;

    protected $listeners = [
        'sendInvoiceToEmail' => 'sendInvoiceToEmail',
    ];

    public function render()
    {
        $term = trim($this->s);

        $model = MerchantReport::query()->search($term)->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->paginate($this->perPage);

        return view('consumption_reports::components.merchantreportcomponent',
            ['model' => $model])->layout('admin::layouts.master');
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

    public function sendInvoiceToEmail($id)
    {
        $report = MerchantReport::find($id);

        $template = TemplateContentService::create()->getTemplateContent('send-invoice-to-merchant', 0, true);
        $templateParser = new ContentParserService();
        //$builder = new HTMLBuilder($order);

        $data['subject'] = $templateParser->parseContent($template->subject, [
            'NAME' => 'yo',
        ]);

        $data['body'] = $templateParser->parseContent($template->description, [
            'NAME' => 'yoyo',
        ]);
        $data['email'] = trim($report->merchant_email);
        $data['storeId'] = 0;
        $files = [
            app_path().'/Components/Szamlazz/pdf/'.$report->invoice_url.'.pdf',
        ];

        Mail::send('templates::email.common', $data, function ($message) use ($data, $files) {
            $message->to($data['email'], $data['email'])
                    ->subject($data['subject']);

            foreach ($files as $file) {
                $message->attach($file);
            }
        });
    }
}
