<?php

namespace App\Services;
use Alomgyar\Affiliates\AffiliateRedeem;
use App\Helpers\AffiliateHelper;
use App\Helpers\HumanReadable;
use App\Helpers\SettingsHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use TCPDF;

class GeneratePdfService
{
    protected $affiliateService;
    public function __construct() {
        $this->affiliateService = new AffiliateService;
    }
    public function generateRedeemPdf($redeem)
    {
        if (! $redeem?->customer?->affiliate) {
            return;
        }
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Nyugta');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(1, 4, 1);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetFont('dejavusans', '', 8);
        $pdf->AddPage();

        // pdf data
        $affiliateData = $redeem->customer?->affiliate;
        $pdfRoot = Storage::path('public'.DIRECTORY_SEPARATOR.'redeem-pdfs');
        if (! File::isDirectory($pdfRoot)) {
            File::makeDirectory($pdfRoot, 0775, true, true);
        }

        $pdfFolderPath = $pdfRoot.DIRECTORY_SEPARATOR.$affiliateData->code;
        if (! File::isDirectory($pdfFolderPath)) {
            File::makeDirectory($pdfFolderPath, 0775, true, true);
        }

        $amount = $redeem->amount;
        $dateOfRedeem = (new Carbon($redeem->created_at))->format(config('pamadmin.date-format'));

        $pdfName = $redeem->generateRedeemFileName();

        $affiliateSettings = SettingsHelper::getSettingsByKeys(['minimum_redeem_amount', 'redeems_per_year']);
        $affiliateSettings['minimum_redeem_amount'] = HumanReadable::formatHUF($affiliateSettings['minimum_redeem_amount']);

        $previousRedeem = AffiliateRedeem::select('created_at')->where('id', '<', $redeem->id)->orderBy('id', 'desc')->first();
        $dateOfPreviousRedeem = AffiliateHelper::AFFILIATE_RELEASE_DATE;
        if ($previousRedeem)
        {
            $dateOfPreviousRedeem = $previousRedeem->created_at;
        }
        $dateOfPreviousRedeem =(new Carbon($dateOfPreviousRedeem))->format(config('pamadmin.date-format'));
        $pdfFullFilePath = $pdfFolderPath.DIRECTORY_SEPARATOR.$pdfName.'.pdf';
        $pdf->writeHTML(view('affiliates::redeem', compact('affiliateData', 'amount', 'pdfName', 'dateOfRedeem', 'dateOfPreviousRedeem', 'affiliateSettings')), true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output($pdfFullFilePath, 'F');

        $redeem->pdf = $affiliateData->code . DIRECTORY_SEPARATOR. $pdfName.'.pdf';
        $redeem->save();

        return $redeem->pdf;
    }
}
