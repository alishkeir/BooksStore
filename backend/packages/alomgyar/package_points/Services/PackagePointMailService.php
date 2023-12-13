<?php

namespace Alomgyar\PackagePoints\Services;

use Alomgyar\PackagePoints\Models\PackagePointPackage;
use Alomgyar\Templates\Email\TemplatedMail;
use Alomgyar\Templates\Entity\TemplatedMailEntity;
use Alomgyar\Templates\Services\ContentParserService;
use Alomgyar\Templates\Services\TemplateContentService;
use Illuminate\Support\Facades\Mail;

class PackagePointMailService
{
    protected int $storeId = 0;

    public function sendShippingMail(PackagePointPackage $package)
    {
        $email = trim($package->email);

        $templatedMailEntity = $this->getTemplatedMailEntity('package_point_mail_shipping', $package);

        $this->sendMail($email, $templatedMailEntity);
    }

    public function sendArrivedMail(PackagePointPackage $package)
    {
        $email = trim($package->email);

        $templatedMailEntity = $this->getTemplatedMailEntity('package_point_mail_arrived', $package);

        $this->sendMail($email, $templatedMailEntity);
    }

    private function getTemplatedMailEntity(string $templateSlug, PackagePointPackage $package): TemplatedMailEntity
    {
        $template = TemplateContentService::create()->getTemplateContent($templateSlug, $this->storeId, true);
        $templateParser = new ContentParserService();

        $subject = $templateParser->parseContent($template->subject, [
            'CODE' => $package->code,
        ]);

        $body = $templateParser->parseContent($template->description, [
            'NAME' => $package->customer,
            'CODE' => $package->code,
            'PARTNER_NAME' => $package->partner->name,
            'PARTNER_LINK' => $package->partner->link,
            'SHOP_NAME' => $package->shop->name,
            'SHOP_ADDRESS' => $package->shop->address,
            'SHOP_PHONE' => $package->shop->phone,
            'SHOP_EMAIL' => $package->shop->email,
            'SHOP_OPEN' => str_replace('\\r\\n', '<br>', nl2br($package->shop->open)),
        ]);

        $templateMailEntity = new TemplatedMailEntity();
        $templateMailEntity->setStoreId($this->storeId);
        $templateMailEntity->setSubject($subject);
        $templateMailEntity->setBody($body);

        return $templateMailEntity;
    }

    private function sendMail(string $email, TemplatedMailEntity $templatedMailEntity): void
    {
        Mail::to($email)->send(new TemplatedMail($templatedMailEntity));
    }
}
