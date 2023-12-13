<?php

namespace Alomgyar\Templates\Services;

class AdminHelperService
{
    protected $map = [
        'checkout' => 'checkout',
        'customer-verify' => 'customer-verify',
        'reset-password-notification' => 'reset-password-notification',
        'status_processing' => 'status-change',
        'status_completed' => 'status-change',
        'status_waiting_for_shipping' => 'status-change',
        'status_shipping' => 'status-change',
        'status_landed' => 'status-change',
        'lost-email' => 'lost-email',
        'contact' => 'contact',
        'product-has-normal-status' => 'product-has-normal-status',
        'product-orderable' => 'product-orderable',
        'package_point_mail_shipping' => 'package-point',
        'package_point_mail_arrived' => 'package-point',
        'author_new_book' => 'author-new-book',
    ];

    public static function create(): self
    {
        return new self;
    }

    public function helper(string $slug): ?string
    {
        if (! isset($this->map[$slug])) {
            return null;
        }

        try {
            return view(sprintf('templates::admin.helper.%s', $this->map[$slug]))->render();
        } catch (\Exception $e) {
            return null;
        }
    }
}
