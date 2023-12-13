<?php

namespace Alomgyar\Customers;

use Alomgyar\Customers\Events\CustomerVerifiedEvent;
use Alomgyar\Customers\Requests\CustomerVerifyEmailRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class CustomerVerifyEmailController extends Controller
{
    /**
     * Mark the authenticated customer's email address as verified.
     */
    public function __invoke(CustomerVerifyEmailRequest $request): RedirectResponse
    {
        $customer = $request->customer()->find($request->route('id'));

        $domain = Customer::whichStore($customer);

        if ($customer->hasVerifiedEmail()) {
            return redirect(url($domain.($customer->store == 2 ? '/auth/login' : '').'/#action:feedback|code:302'));
        }

        if ($customer->markEmailAsVerified()) {
            $customer->update(['status' => Customer::STATUS_ACTIVE]);
            event(new CustomerVerifiedEvent($customer));
        }

        return redirect(url($domain.($customer->store == 2 ? '/auth/login' : '').'/#action:feedback|code:301'));
    }
}
